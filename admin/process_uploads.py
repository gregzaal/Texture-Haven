import os
import sys
import shutil
import json
import zipfile
import warnings
from PIL import Image
from subprocess import call as run
from math import floor
from pprint import pprint

script_dir = os.path.dirname(os.path.realpath(__file__))
script_dir = script_dir+'/' if not script_dir.endswith('/') else ''

def get_config():
    cf = os.path.join(script_dir, 'process_uploads.cfg')
    if not os.path.exists(cf):
        print ("Config file doesn't exist!")
        sys.exit(0)
    with open(cf, 'r') as f:
        data = json.load(f)
    return data


config = get_config()
input_folder = config['input_path']
output_folder = config['output_path']
imagemagick_convert = config['imagemagick_convert_exe']


def qmkdir(p):
    if not os.path.exists(p):
        os.makedirs(p)

def make_res_str(res):
    return str(res)+"k"

def do_resize(img, size, out, compression=85):
    print (os.path.basename(out))
    cmd = [imagemagick_convert]
    cmd.append(img)
    cmd.append("-filter")
    cmd.append("sinc")
    cmd.append("-resize")
    cmd.append(str(size)+"x")
    cmd.append("-quality")
    cmd.append(str(compression))
    cmd.append(out)
    run(cmd)


def make_jpg(img, compression=95):
    dirname = os.path.dirname(img)
    name, ext = os.path.splitext(os.path.basename(img))
    out = os.path.join(dirname, name+'.jpg')
    cmd = [imagemagick_convert]
    cmd.append(img)
    cmd.append("-format")
    cmd.append("jpg")
    cmd.append("-quality")
    cmd.append(str(compression))
    cmd.append(out)
    run(cmd)
    return out

def make_map_preview(slug, name, map_preview_source, compression=85):
    map_name = name[len(slug)+1:]
    previews_dir = os.path.join(output_folder, "tex_images", "map_previews", slug)
    qmkdir(previews_dir)
    out = os.path.join(previews_dir, map_name+".jpg")
    cmd = [imagemagick_convert]
    cmd.append(map_preview_source)
    cmd.append("-resize")
    cmd.append("640x640")
    cmd.append("-format")
    cmd.append("jpg")
    cmd.append("-quality")
    cmd.append(str(compression))
    cmd.append(out)
    run(cmd)

def do_resolutions(slug, img, original_size, outpath):
    name, ext = os.path.splitext(os.path.basename(img))
    resolutions = []
    aspect = original_size[0] / original_size[1]
    res_int_max = floor(max(original_size[0], original_size[1])/1000)
    return_files = {}  # Returned for zip function
    i = 0
    r = 0
    while True:
        r = pow(2,i)
        if r < res_int_max:
            resolutions.append(r)
            i += 1
        else:
            break
    resolutions.append(res_int_max)
    
    resolutions.reverse()
    map_preview_source = img  # Default to original image, but rather use 1k
    prev_img = img  # Use previous resized image for faster processing
    print(name, end='', flush=True)
    for r in resolutions:
        res_str = make_res_str(r)
        res_actual = r*1024
        res_path = os.path.join(outpath, "textures", slug, res_str)
        qmkdir(res_path)
        out = os.path.join(res_path, name+"_"+res_str+".png")
        print (' '+res_str, end='\n' if r == resolutions[-1] else '', flush=True)
        if r == res_int_max:
            shutil.copy2(img, out)
        else:
            cmd = [imagemagick_convert]
            cmd.append(prev_img)
            cmd.append("-resize")
            cmd.append(str(res_actual)+"x"+str(res_actual))
            cmd.append(out)
            run(cmd)
        prev_img = out

        if r in return_files:
            return_files[r].append(out)
        else:
            return_files[r] = [out]

        jpg = make_jpg(out)
        return_files[r].append(jpg)

        if r == 1:
            # Use 1k for generating map_preview
            map_preview_source = out

    make_map_preview(slug, name, map_preview_source)

    return return_files

def make_zips(slug, r, files):
    # Unused, done on server
    # Split into extensions
    extensions = {}
    for f in files:
        name, ext = os.path.splitext(f)
        ext = ext[1:]  # Remove '.'
        if ext in extensions:
            extensions[ext].append(f)
        else:
            extensions[ext] = [f]

    for ext in extensions:
        zfn = slug+'_'+str(r)+'k_'+ext+'.zip'
        print ("Zipping", zfn)
        zfp = os.path.join(os.path.dirname(f), zfn)
        z = zipfile.ZipFile(zfp, "w")
        for f in extensions[ext]:
            fname = os.path.basename(f)
            z.write(f, fname)
        z.close()
        
def main():

    subfolders = os.listdir(input_folder)
    checked = 0
    processed = 0
    errors = []
    for slug in subfolders:
        sf = os.path.join(input_folder, slug)
        if os.path.isdir(sf):
            all_new_files = {}
            errors_here = False
            for f in os.listdir(sf):
                checked += 1
                if f.startswith(slug):
                    processed += 1
                    fp = os.path.join(sf, f)
                    Image.MAX_IMAGE_PIXELS = None
                    warnings.simplefilter('ignore', Image.DecompressionBombWarning)
                    original_size = Image.open(fp).size
                    new_files = do_resolutions(slug, fp, original_size, output_folder)
                    for r in new_files:
                        if r in all_new_files:
                            all_new_files[r] = new_files[r] + list(set(all_new_files[r]) - set(new_files[r]))
                        else:
                            all_new_files[r] = new_files[r]
                else:
                    errors.append([slug, f])
                    errors_here = True
                    print("Error:", str(errors[-1]))
            if not errors_here:
                for r in all_new_files:
                    make_zips(slug, r, all_new_files[r])
                shutil.rmtree(sf)  # Delete successfully uploaded folder
    print ("Checked:", checked, " -  Processed:", processed, " -  Errors:", len(errors))
    if errors:
        print ("Errors:")
        pprint (errors)

main()

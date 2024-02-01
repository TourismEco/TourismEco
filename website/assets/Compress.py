from PIL import Image
import os 

reper="D:/wamp64/www/eco/website/assets/img"
try:
    os.makedirs(reper+"/Compress")
except:
    pass
for root, dirs, files in os.walk(reper): 
    for file in files:
        try:
            im=Image.open(reper+"/"+file)
            if im.width > 2500:
                im = im.resize((2500,int(2500*im.height/im.width)))
                im.save(reper+"/Compress/"+file)
        except:
            print(file)

print("FINI")
from PIL import Image
import os 

reper="D:/wamp64/www/eco/website/assets/img"
try:
    os.makedirs(reper+"/mini")
except:
    pass
for root, dirs, files in os.walk(reper): 
    for file in files:
        try:
            im=Image.open(reper+"/"+file)
            if im.width > 200:
                im = im.resize((200,int(200*im.height/im.width)))
                im.save(reper+"/mini/"+file)
        except:
            print(file)

print("FINI")
from generic import readCSV
from sql import connectSQL

db = "projet"

def listAircraft():
    cnx, cur = connectSQL(db)
    liste = []
    pres = ["100","141","142","143","146","310","318","319","320","321","332","333","342","343","345","346","380","717","732","734","735","736","737","738","739","73G","744","747","74L","752","753","762","763","764","772","773","77L","77W","788","A58","A81","AB6","AN4","AR1","AR8","AT4","AT5","AT7","ATP","ATR","BEH","BH2","BNI","CR2","CR7","CR9","CRA","CRK","D1C","D28","D38","D93","DH1","DH2","DH3","DH4","DH8","DHP","DHT","E70","E75","E90","E95","EM2","EMB","ER3","ER3","ER4","ERD","F50","F70","FRJ","I14","J31","J32","J41","L4T","M11","M80","M82","M83","M87","M88","M90","MA6","S20","SF3","SH6","SU9","SWM","T20","YK2","787","777","76W","767","75T","757","74M","74E","73W","73M","73J","73H","737","388","340","330","32C","32B","32A","313","32S","CRJ"]
    for i in cur.execute("SELECT * FROM routes").fetchall():
        for j in i["equipment"].split(" "):
            if "\r" in j:
                j = j[:-1]
            if j not in liste:
                liste.append(j)
    liste.sort()
    print(liste)
    print(len(liste))
    print(list(filter(lambda x:x not in pres,liste)))

def checkAircraft():
    cnx, cur = connectSQL(db)
    planes = readCSV("planes2")

    for i in planes:
        cur.execute(f"UPDATE planes SET `check` = 1 WHERE `name` = '{i['Aircraft']}'")
    
    cnx.commit()

def checkRoutes():
    cnx, cur = connectSQL(db)
    liste = []
    for i in cur.execute("SELECT * FROM planes WHERE `check` = 1").fetchall():
        for j in cur.execute(f"SELECT * FROM routes WHERE equipment LIKE '%{i['iata']}%' OR equipment LIKE '%{i['alter']}%'").fetchall():
            if j["rid"] not in liste:
                liste.append(j["rid"])
    print(len(liste))

def rankAircraft():
    cnx, cur = connectSQL(db)
    dictA = {}
    for i in cur.execute("SELECT * FROM routes").fetchall():
        for j in i["equipment"].split(" "):
            if "\r" in j:
                j = j[:-1]
            if j not in dictA:
                dictA[j] = 0
            dictA[j] += 1
    
    for i in cur.execute("SELECT * FROM planes WHERE `alter` IS NOT NULL").fetchall():
        dictA[i["iata"]]+=dictA[i["alter"]]
        del dictA[i["alter"]]
    
    for i in cur.execute("SELECT * FROM planes WHERE `check` IS NOT NULL").fetchall():
        del dictA[i["iata"]]
    
    dictA = dict(sorted(dictA.items(), key=lambda item: item[1], reverse=True))
    print(dictA)

def cleanRoutes():
    cnx, cur = connectSQL(db)
    if False:
        for i in cur.execute("SELECT * FROM routes").fetchall():
            l = []
            for j in i["equipment"].split(" "):
                if "\r" in j:
                    j = j[:-1]
                l.append(j)
            cur.execute(f"UPDATE routes SET equipment = '{' '.join(l)}' WHERE rid = {i['rid']}")

        cnx.commit()
    
        for i in cur.execute("SELECT * FROM planes WHERE `alter` IS NOT NULL").fetchall():
            for j in cur.execute(f"SELECT * FROM routes WHERE equipment LIKE '%{i['alter']}%'").fetchall():
                l = []
                for z in j["equipment"].split(" "):
                    if z == i["alter"]:
                        l.append(i["iata"])
                    else:
                        l.append(z)
                cur.execute(f"UPDATE routes SET equipment = '{' '.join(l)}' WHERE rid = {j['rid']}")

        for i in cur.execute(f"SELECT * FROM routes WHERE equipment LIKE '%734%'").fetchall():
            l = []
            for z in i["equipment"].split(" "):
                if z == "734":
                    l.append("736")
                else:
                    l.append(z)
            cur.execute(f"UPDATE routes SET equipment = '{' '.join(l)}' WHERE rid = {i['rid']}")

        for i in cur.execute("SELECT * FROM routes").fetchall():
            l = []
            for j in i["equipment"].split(" "):
                if j not in l:
                    l.append(j)
            cur.execute(f"UPDATE routes SET equipment = '{' '.join(l)}' WHERE rid = {i['rid']}")
    
    for i in cur.execute("SELECT * FROM routes").fetchall():
        l = []
        for j in i["equipment"].split(" "):
            if j in ["AT5","ATR","AT7","319","320","321","332","333","343","380","A81","A58","BEH","736","73G","738","744","752","753","762","763","764","772","77L","773","77W","788","CRK","CR2","CR7","CR9","DH4","E70","E75","E90","E95","EM2","ER3","ER4","S20","SF3","SU9"]:
                l.append(j)
        cur.execute(f"UPDATE routes SET equipment = '{' '.join(l)}' WHERE rid = {i['rid']}")
    
    cnx.commit()

def addConso():
    cnx, cur = connectSQL(db)
    planes = readCSV("planes2")

    for i in planes:
        print(i)
        id = cur.execute(f"SELECT * FROM planes WHERE name = '{i['Aircraft']}'").fetchone()["id"]
        cur.execute(f"INSERT INTO planes_conso (id_plane,annee,places,distance_max,fuel_burn,fuel_eff) VALUES ('{id}',{i['Year']}, {i['Seats']}, {i['Sector']}, {i['Fuel burn']}, {i['Fuel eff/seat']})")
    
    cnx.commit()
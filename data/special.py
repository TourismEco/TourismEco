import pandas as pd
from generic import readCSV, isos2
from sql import connectSQL

db = "projet"

def baseCsvAdd():
    """Lit les données de la feuille excel 'allData', contenant des statistiques sur le tourisme par pays. Certaines pages nécessitent de faire des calculs pour obtenir toutes les données, cette fonction cumule donc sur-mesure les données des pages concernées."""

    #nameSheet = ["Inbound Tourism-Expenditure","Outbound Tourism-Expenditure"]
    #nameCsv = ["Recettes","Depenses"]

    nameSheet = ["Outbound Tourism-Departures"]
    nameCsv = ["Departs"]

    for z in range(len(nameSheet)):

        with open(f"data/csv/{nameCsv[z]}.csv","w",encoding="UTF-8") as file:

            df = pd.read_excel("data/allData.xlsx",nameSheet[z],header=5,)
            print(len(df))

            liste = [i for i in range(1995,2022)]

            file.write("Pays;1995;1996;1997;1998;1999;2000;2001;2002;2003;2004;2005;2006;2007;2008;2009;2010;2011;2012;2013;2014;2015;2016;2017;2018;2019;2020;2021\n")

            for row in range(0,1115,5):
                string = str(df["Basic data and indicators"][row])

                for col in liste:
                    if df[col][row+3] == ".." and df[col][row+4] == "..":
                        string+=";NULL"
                    elif df[col][row+3] == "..":
                        string+=f";{df[col][row+4]}"
                    elif df[col][row+4] == "..":
                        string+=f";{df[col][row+3]}"
                    else:
                        string+=f";{df[col][row+3]+df[col][row+4]}"

                file.write(string+"\n")

        file.close()

def updateArrivees():
    """Une faille a été relevée dans la table `arrivees` de notre base de données. Pour certains pays, aucunes valeurs n'étaient entrées car il nécessitait un calcul intermédiaire qui n'avait pas été remarqué. Cette fonction récupère et calcule les valeurs à calculer."""
    nameSheet = [" Inbound Tourism-Arrivals"]
    nameCsv = ["Arriveesv2"]

    for z in range(len(nameSheet)):

        with open(f"data/csv/{nameCsv[z]}.csv","w",encoding="UTF-8") as file:

            df = pd.read_excel("data/xlsx/allData.xlsx",nameSheet[z],header=5,)
            print(len(df))

            liste = [i for i in range(1995,2022)]

            file.write("Pays;1995;1996;1997;1998;1999;2000;2001;2002;2003;2004;2005;2006;2007;2008;2009;2010;2011;2012;2013;2014;2015;2016;2017;2018;2019;2020;2021\n")

            for row in range(0,1341,6):
                val = 0
                string = str(df["Basic data and indicators"][row])

                for col in liste:
                    if df[col][row+2] == "..":
                        nb = 0
                        bo = [df[col][row+3]!="..", df[col][row+4]!="..", df[col][row+5]!=".."]
                        for i,e in enumerate(bo):
                            if e:
                                nb += df[col][row+3+i]
                        
                        if nb == 0:
                            string+=";NULL"
                        else:
                            string+=f";{nb}"
                            val += 1
                
                if val != 0:
                    file.write(string+"\n")

        file.close()

def aggloUpdate():
    """Fonction complémentaire à `updateArrivees()`, une fois les données récupérées, un traitement est effectué à la manière de la fonction générique `agglomerate`. Les lignes vides sont retirées et les noms de pays remplacés, puis les données sont transmises à la base de données."""
    global db
    table = readCSV("Arriveesv2")
    codes = readCSV("paysEN")
    cnx, cur = connectSQL(db)

    dictCodes = {}
    for i in codes:
        dictCodes[i["name"].upper()] = i["country"]
    dictCodes['BOLIVIA, PLURINATIONAL STATE OF'] = 'BO' 
    dictCodes['BRUNEI DARUSSALAM'] = 'BN' 
    dictCodes['CABO VERDE'] = 'CV' 
    dictCodes['CONGO'] = 'CG' 
    dictCodes['CONGO, DEMOCRATIC REPUBLIC OF THE'] = 'CD' 
    dictCodes['CZECH REPUBLIC (CZECHIA)'] = 'CZ' 
    dictCodes['HONG KONG, CHINA'] = 'HK' 
    dictCodes['IRAN, ISLAMIC REPUBLIC OF'] = 'IR' 
    dictCodes['KOREA, DEMOCRATIC PEOPLE´S REPUBLIC OF'] = 'KP' 
    dictCodes['KOREA, REPUBLIC OF'] = 'KR' 
    dictCodes['LAO PEOPLE´S DEMOCRATIC REPUBLIC'] = 'LA' 
    dictCodes['MACAO, CHINA'] = 'MO' 
    dictCodes['MICRONESIA, FEDERATED STATES OF'] = 'FM' 
    dictCodes['MOLDOVA, REPUBLIC OF'] = 'MD' 
    dictCodes['MYANMAR'] = 'MM' 
    dictCodes['NORTH MACEDONIA'] = 'MK' 
    dictCodes['RUSSIAN FEDERATION'] = 'RU' 
    dictCodes['SERBIA AND MONTENEGRO'] = 'RS' 
    dictCodes['STATE OF PALESTINE'] = 'PS' 
    dictCodes['SYRIAN ARAB REPUBLIC'] = 'SY' 
    dictCodes['TAIWAN PROVINCE OF CHINA'] = 'TW' 
    dictCodes['TANZANIA, UNITED REPUBLIC OF'] = 'TZ' 
    dictCodes['UNITED STATES OF AMERICA'] = 'US' 
    dictCodes['UNITED STATES VIRGIN ISLANDS'] = 'VI' 
    dictCodes['VENEZUELA, BOLIVARIAN REPUBLIC OF'] = 'VE' 
    dictCodes['VIET NAM'] = 'VN' 
    dictCodes['TÜRKIYE'] = 'TR' 

    final = []

    for i in range(1995,2022):
        for j in range(120):
            try:
                dictA = {"Pays":dictCodes[table[j]["Pays"]],"Annee":i}
            except: # pays pas dans la liste
                continue
            print(table[j][str(i)],type(table[j][str(i)]))
            dictA["Arrivee"] = table[j][str(i)]
            if table[j][str(i)] not in (None,"NULL"):
                final.append(dictA.copy())
    
    for i in final:
        cur.execute(f"UPDATE arrivees SET arriveesTotal={i['Arrivee']} WHERE id_pays='{i['Pays']}' AND annee={i['Annee']}")
    cnx.commit()
    return final

def addCodes():
    """Cette fonction a servi à compléter la table `pays`, pour y ajouter les codes ISO alpha_3 et numériques. La table a été ALTER en amont."""
    global db
    codes = readCSV("iso")
    cnx, cur = connectSQL(db)

    for i in codes:
        cur.execute(f"UPDATE pays SET A3 = '{i['A3']}', Num={i['Num']} WHERE id = '{i['A2']}'")

    cnx.commit()

    # DELETE FROM pays WHERE id = 'BV' OR id = 'AN' OR id = 'GZ' OR id = 'HM' OR id = 'SJ';

def addEmojis():
    """Cette fonction a servi à compléter la table `pays`, pour y ajouter les emojis drapeaux de chaque pays. La table a été ALTER en amont."""
    global db
    emojis = readCSV("countries")
    cnx, cur = connectSQL(db)

    for i in emojis:
        cur.execute(f"UPDATE pays SET emoji = '{i['emoji']}', emojiU='{i['emojiU']}' WHERE id = '{i['iso2']}'")

    cnx.commit()
    cnx.close()

def addEmojisFile():
    emojis = readCSV("countries")
    with open("data/sql/emojis.sql","w",encoding="utf-8") as file:
        for i in emojis:
            file.write(f"UPDATE pays SET emoji = '{i['emoji']}' WHERE id = '{i['iso2']}';\n")
    file.close()

def filterCsv():
    """Cette fonction a permis de trier le fichier CSV SYB65_128_202209_Consumer Price Index, qui contenait plusieurs catégories de CPI. Seul le CPI global a été conservé pour la suite de notre projet."""
    liste = readCSV("SYB65_128_202209_Consumer Price Index")
    newListe = []

    for i in liste:
        if i["Series"] == "Consumer price index: General":
            newListe.append({"Pays":i["id"],"Annee":i["Year"],"Value":i["Value"]})
    
    return newListe

def updateRenew():
    global db

    cnx, cur = connectSQL(db)
    csv = readCSV("share-electricity-renewables")
    isos = readCSV("iso")
    to2 = {i["A3"]:i["A2"] for i in isos}

    for i in csv:
        if i["Code"] != "" and int(i["Year"]) >= 1990 and int(i["Year"]) <= 2020:  
            cur.execute(f"UPDATE ecologie SET elecRenew={i['Value']} WHERE id_pays='{to2[i['Code']]}' AND annee={i['Year']}")
    cnx.commit()

def addPibCapita():
    global db

    cnx, cur = connectSQL(db)
    csv = readCSV("gdp-per-capita-worldbank")
    isos = readCSV("iso")
    to2 = {i["A3"]:i["A2"] for i in isos}

    for i in csv:
        if i["Code"] != "" and int(i["Year"]) >= 1995 and int(i["Year"]) <= 2021:  
            cur.execute(f"UPDATE pib SET pibParHab={i['GDPcapita']} WHERE id_pays='{to2[i['Code']]}' AND annee={i['Year']}")
    cnx.commit()

def addTwemoji():
    global db

    cnx, cur = connectSQL(db)
    for i in cur.execute("SELECT * FROM pays").fetchall():
        u = i["emojiU"]
        li = u.split(" ")
        li = list(map(lambda x:x[2:].lower(),li))
        li = "-".join(li)
        cur.execute(f"UPDATE pays SET emojiSVG='{li}' WHERE id = '{i['id']}';")
    cnx.commit()

def addContinent():
    global db
    csv = readCSV("countries")
    cnx, cur = connectSQL(db)
    dictCo = {"1":1,"3":4,"4":5,"5":6}
    for i in csv:
        if i["region_id"] == '2':
            if i["subregion_id"] == '8':
                cur.execute(f"UPDATE pays SET id_continent = 3 WHERE id = '{i['iso2']}'")
            else:
                cur.execute(f"UPDATE pays SET id_continent = 2 WHERE id = '{i['iso2']}'")
        elif i["region_id"] in ("6",""):
            pass
        else:
            cur.execute(f"UPDATE pays SET id_continent = {dictCo[i['region_id']]} WHERE id = '{i['iso2']}'")
    cnx.commit()

def renameEmojis():
    import os
    cnx, cur = connectSQL(db)
    for i in cur.execute("SELECT id, emojiSVG FROM pays").fetchall():
        os.rename(f"website/assets/twemoji/{i['emojiSVG']}.svg",f"website/assets/twemoji/{i['id']}.svg")


def addPop():
    csv = readCSV("Population")
    liste = []

    for i in csv:
        if i["ISO2_code"] not in isos2 or int(i["Time"]) < 1990 or int(i["Time"]) > 2022:
            continue
        liste.append({"id":i["ISO2_code"],"annee":i["Time"],"pop":i["TPopulation1Jan"],"densite":i["PopDensity"]})
    
    cnx, cur = connectSQL(db)
    cur.execute("DROP TABLE IF EXISTS population")
    cur.execute("CREATE TABLE IF NOT EXISTS population (id INT PRIMARY KEY, id_pays VARCHAR(3), annee INT, population DOUBLE, densite DOUBLE)")

    for i,e in enumerate(liste):
        cur.execute(f"INSERT INTO population VALUES ({i},'{e['id']}',{e['annee']},{e['pop']},{e['densite']})")
    
    cnx.commit()

def addGrowth(table,cols):
    cnx, cur = connectSQL(db)
    for col in cols:
        for i in isos2:
            get = cur.execute(f"""SELECT id_pays, annee, {col},
            IF(@last_entry = 0, 0, round((({col} - @last_entry) / @last_entry) * 100,2)) "grow",
            @last_entry := {col} AS tmp
            FROM
            (SELECT @last_entry := 0) x,
            (SELECT id_pays, annee, sum({col}) {col}
            FROM {table}
            WHERE id_pays = "{i}"
            GROUP BY annee) y;""").fetchall()

            for j in get:
                if j["grow"] is None:
                    j["grow"] = 0
                cur.execute(f"UPDATE {table}_grow SET {col} = {j['grow']} WHERE id_pays = '{i}' AND annee = {j['annee']}")
            
    cnx.commit()

def setVilles():
    cnx, cur = connectSQL(db)
    for i in cur.execute("SELECT * FROM airports").fetchall():
        a = cur.execute(f"SELECT * FROM villes WHERE nom = '{i['city']}' AND id_pays = '{i['id_pays']}'").fetchone()
        if a != None:
            cur.execute(f"UPDATE airports SET city = '{a['id']}' WHERE apid = {i['apid']}")
    cnx.commit()

    
def addDescip(tables):

    cnx, cur = connectSQL(db)

    for z in range(len(tables)):

        df = pd.read_excel("data/xlsx/descip.xlsx",tables[z],header=0)
        
        for i in range(len(df)):
            if not pd.isna(df['Anecdote 1'][i]) and not pd.isna(df['Anecdote 2'][i]) and not pd.isna(df['Anecdote 3'][i]):

                print((df['Description'][i],df['Anecdote 1'][i],df['Anecdote 2'][i],df['Anecdote 3'][i]))
                cur.execval(f"UPDATE pays SET description = %s, sv1 = %s, sv2 = %s, sv3 = %s WHERE id = '{df['id'][i]}'",(df['Description'][i],df['Anecdote 1'][i],df['Anecdote 2'][i],df['Anecdote 3'][i]))

    cnx.commit()




# addEmojisFile()

#updateRenew()
# addPibCapita()
#addTwemoji()

# addContinent()

# renameEmojis()

# addPop()
    
# addGrowth("economie",["pib","pibParHab","cpi"])
# addGrowth("tourisme",["arriveesTotal","arriveesAF","arriveesAM","arriveesEA","arriveesEU","arriveesME","arriveesSA","arriveesAutre","arriveesPerso","arriveesPro","arriveesAvion","arriveesEau","arriveesTerre","departs","depenses","recettes","emplois"])
# addGrowth("surete",["gpi"])
# addGrowth("ecologie",["co2","ges","elecRenew"])
    
# setVilles()
    
addDescip(["Aya","Cassandra","Line","Rémy","Lucas"])
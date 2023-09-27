import pandas as pd 
from sql import connectSQL

def baseCsvAdd():
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


def baseCsv():
    """nameSheet = [" Inbound Tourism-Arrivals","Inbound Tourism-Regions","Inbound Tourism-Regions","Inbound Tourism-Regions","Inbound Tourism-Regions","Inbound Tourism-Regions","Inbound Tourism-Regions","Inbound Tourism-Regions","Inbound Tourism-Purpose","Inbound Tourism-Purpose"]
    nameCsv = ["Arrivees","ArriveesAF","ArriveesAM","ArriveesEA","ArriveesEU","ArriveesME","ArriveesSA","ArriveesAutre","ArriveesPerso","ArriveesPro"]
    maxRow = [1341,2451,2451,2451,2451,2451,2451,2451,1120,1120]
    pad = [6,11,11,11,11,11,11,11,5,5]
    plus = [2,3,4,5,6,7,8,9,3,4]
    head = 5"""

    """nameSheet = ["Inbound Tourism-Transport","Inbound Tourism-Transport","Inbound Tourism-Transport"]
    nameCsv = ["ArriveeAvion","ArriveesEau","ArriveeTerre"]
    maxRow = [1341,1341,1341]
    pad = [6,6,6]
    plus = [3,4,5]"""

    """nameSheet = ["Overall Scores"]
    nameCsv = ["GPI"]
    maxRow = [163]
    pad = [1]
    plus = [0]
    head = 3"""

    """nameSheet = ["Data"]
    nameCsv = ["ElecRenew"]
    maxRow = [266]
    pad = [1]
    plus = [0]
    head = 3"""

    """nameSheet = ["Employment"]
    nameCsv = ["emploi"]
    maxRow = [2007]
    pad = [9]
    plus = [2]
    head = 5"""

    nameSheet = ["Sheet"]
    nameCsv = ["gdp"]
    maxRow = [217]
    pad = [1]
    plus = [0]
    head = 0


    isos = ["AFG","ZAF","ALB","DZA","DEU","AND","AGO","AIA","ATA","ATG","SAU","ARG","ARM","ABW","AUS","AUT","AZE","BHS","BHR","BGD","BRB","BEL","BLZ","BMU","BTN","BLR","BOL","BIH","BWA","BRN","BRA","BGR","BFA","BDI","BEN","KHM","CMR","CAN","CPV","CHL","CHN","CXR","CYP","CCK","COL","COM","COD","COG","COK","PRK","KOR","CRI","HRV","CUB","CIV","DNK","DJI","DMA","ESP","EST","FJI","FIN","FRA","GAB","GMB","GHA","GIB","GRD","GRL","GRC","GLP","GUM","GTM","GGY","GIN","GNQ","GNB","GUY","GUF","GEO","SGS","HTI","HND","HKG","HUN","IND","IDN","IRQ","IRN","IRL","ISL","ISR","ITA","JAM","JPN","JEY","JOR","KAZ","KEN","KGZ","KIR","XKX","KWT","LAO","LSO","LVA","LBN","LBY","LBR","LIE","LTU","LUX","MAC","MKD","MDG","MYS","MWI","MDV","MLI","MLT","IMN","MNP","MAR","MTQ","MUS","MRT","MYT","MEX","FSM","MDA","MCO","MNG","MSR","MNE","MOZ","MMR","NAM","NRU","NIC","NER","NGA","NIU","NFK","NOR","NCL","NZL","NPL","OMN","UGA","UZB","PAK","PLW","PAN","PNG","PRY","NLD","PHL","PCN","POL","PYF","PRI","PRT","PER","QAT","ROU","GBR","RUS","RWA","CAF","DOM","CZE","REU","ESH","KNA","SMR","SPM","VCT","SHN","LCA","SLB","SLV","WSM","ASM","SRB","SYC","SLE","SGP","SVK","SVN","SOM","SDN","LKA","CHE","SUR","SWE","SWZ","SYR","STP","SEN","TJK","TZA","TWN","TCD","ATF","IOT","PSE","THA","TGO","TKL","TON","TTO","TUN","TKM","TUR","TUV","UKR","USA","URY","VUT","VEN","VIR","VGB","VNM","WLF","YEM","ZMB","ZWE","EGY","ARE","ECU","ERI","VAT","ETH","CYM","FLK","FRO","MHL","TCA"]

    #print(df[1995][])

    for z in range(len(nameSheet)):

        with open(f"data/csv/{nameCsv[z]}.csv","w",encoding="UTF-8") as file:

            df = pd.read_excel("data/xlsx/Data_Extract_FromWorld Development Indicators.xlsx",nameSheet[z],header=head)
            print(len(df))

            liste = [str(i) for i in range(1995,2023)]
            #liste = [i for i in range(2008,2024)]
            #liste = [f"{i}" for i in range(1990,2021)]

            file.write("Pays;1995;1996;1997;1998;1999;2000;2001;2002;2003;2004;2005;2006;2007;2008;2009;2010;2011;2012;2013;2014;2015;2016;2017;2018;2019;2020;2021;2022\n")
            #file.write("Pays;2008;2009;2010;2011;2012;2013;2014;2015;2016;2017;2018;2019;2020;2021;2022;2023\n")
            #file.write("Pays;1990;1991;1992;1993;1994;1995;1996;1997;1998;1999;2000;2001;2002;2003;2004;2005;2006;2007;2008;2009;2010;2011;2012;2013;2014;2015;2016;2017;2018;2019;2020\n")

            for row in range(0,maxRow[z],pad[z]):
                #string = str(df["Basic data and indicators"][row])
                #string = str(df["Country Code"][row])
                string = str(df["Country"][row])

                """if string not in isos:
                    continue"""

                for col in liste:
                    if df[col][row+plus[z]] == "..":
                    #if pd.isna(df[col][row+plus[z]]):
                        string+=";NULL"
                    else:
                        string+=f";{df[col][row+plus[z]]}"

                file.write(string+"\n")

        file.close()


def readCSV(filename):
    import csv

    table = []
    file = open("data/csv/"+filename+".csv", encoding="utf-8-sig", newline="\n")
    for ligne in csv.DictReader(file,delimiter=";"):
        table.append(dict(ligne))
    file.close()

    return table

def agglomerate():
    from copy import deepcopy

    #tags = ["Arrivees","ArriveesAF","ArriveesAM","ArriveesEA","ArriveesEU","ArriveesME","ArriveesSA","ArriveesAutre","ArriveesPerso","ArriveesPro","ArriveeAvion","ArriveesEau","ArriveeTerre"]
    #tags = ["Depenses","Recettes"]
    #tags = ["Departs"]
    #tags = ["GPI"]
    #tags = ["emploi"]
    tags = ["gdp"]

    allTable =[]
    for i in tags:
        allTable.append(deepcopy(readCSV(i)))

    final = []

    for i in range(1995,2023):
        for j in range(217):
            dictA = {"Pays":allTable[0][j]["Pays"],"Annee":i}
            for z, t in enumerate(allTable):
                dictA[tags[z]] = t[j][str(i)]
            final.append(dictA.copy())
    
    return final


def toCSV():

    #final = agglomerate()
    #file=open("data/csv/end.csv", "w", encoding="utf-8-sig")

    final = filterCsv()
    file = file=open("data/csv/CPI.csv", "w", encoding="utf-8-sig")

    ajout_key=""
    for key in final[0].keys():
        ajout_key=f"{key};"

    file.write(ajout_key[:-1])

    for i in range(len(final)):
        ajout_value=""
        for value in final[i].values():
            ajout_value+=f"{value};"

        file.write("\n"+ajout_value[:-1])

def toSQL():
    final = agglomerate()
    #final = readCSV("CPI")

    cnx, cur = connectSQL("projet")
    #cur.execute("CREATE TABLE IF NOT EXISTS arrivees (id INT PRIMARY KEY AUTO_INCREMENT, id_pays VARCHAR(50), annee INT, arriveesTotal INT, arriveesAF INT, arriveesAM INT, arriveesEA INT, arriveesEU INT, arriveesME INT, arriveesSA INT, arriveesAutre INT, arriveesPerso INT, arriveesPro INT, arriveesAvion INT, arriveesEau INT, arriveesTerre INT)")
    #cur.execute("CREATE TABLE IF NOT EXISTS pays (id VARCHAR(3) PRIMARY KEY, lat FLOAT, lon FLOAT, nom VARCHAR(50))")
    #cur.execute("CREATE TABLE IF NOT EXISTS argent (id INT PRIMARY KEY AUTO_INCREMENT, id_pays VARCHAR(50), annee INT, depenses INT, recettes INT)")
    #cur.execute("CREATE TABLE IF NOT EXISTS departs (id INT PRIMARY KEY AUTO_INCREMENT, id_pays VARCHAR(50), annee INT, departs INT)")
    #cur.execute("CREATE TABLE IF NOT EXISTS gpi (id INT PRIMARY KEY AUTO_INCREMENT, id_pays VARCHAR(3), annee INT, gpi DOUBLE)")
    #cur.execute("CREATE TABLE IF NOT EXISTS cpi (id INT PRIMARY KEY AUTO_INCREMENT, id_pays VARCHAR(3), annee INT, cpi DOUBLE)")
    #cur.execute("CREATE TABLE IF NOT EXISTS emploi (id INT PRIMARY KEY AUTO_INCREMENT, id_pays VARCHAR(50), annee INT, emplois INT)")
    cur.execute("CREATE TABLE IF NOT EXISTS pib (id INT PRIMARY KEY AUTO_INCREMENT, id_pays VARCHAR(50), annee INT, pib BIGINT)")

    for i,e in enumerate(final):
        ajout_value=""
        for value in e.values():
            if value == "NULL":
                ajout_value+="NULL,"
            elif type(value) == str:
                ajout_value+=f'"{value}",'
            else:
                ajout_value+=f"{value},"

        cur.execute(f"INSERT INTO pib VALUES ({1+i},{ajout_value[:-1]})")

    
    cnx.commit()
    cnx.close()


def nameToCode():
    codes = readCSV("paysEN")
    cnx, cur = connectSQL("projet")
    nom = "pib"

    for i in codes:
        cur.execute(f"UPDATE {nom} SET id_pays = '{i['country']}' WHERE id_pays = '{i['name'].upper()}'")

    cur.execute(f"UPDATE {nom} SET id_pays = 'BO' WHERE id_pays = 'BOLIVIA, PLURINATIONAL STATE OF'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'BN' WHERE id_pays = 'BRUNEI DARUSSALAM'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'CV' WHERE id_pays = 'CABO VERDE'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'CG' WHERE id_pays = 'CONGO'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'CD' WHERE id_pays = 'CONGO, DEMOCRATIC REPUBLIC OF THE'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'CZ' WHERE id_pays = 'CZECH REPUBLIC (CZECHIA)'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'HK' WHERE id_pays = 'HONG KONG, CHINA'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'IR' WHERE id_pays = 'IRAN, ISLAMIC REPUBLIC OF'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'KP' WHERE id_pays = 'KOREA, DEMOCRATIC PEOPLE´S REPUBLIC OF'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'KR' WHERE id_pays = 'KOREA, REPUBLIC OF'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'LA' WHERE id_pays = 'LAO PEOPLE´S DEMOCRATIC REPUBLIC'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'MO' WHERE id_pays = 'MACAO, CHINA'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'FM' WHERE id_pays = 'MICRONESIA, FEDERATED STATES OF'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'MD' WHERE id_pays = 'MOLDOVA, REPUBLIC OF'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'MM' WHERE id_pays = 'MYANMAR'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'MK' WHERE id_pays = 'NORTH MACEDONIA'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'RU' WHERE id_pays = 'RUSSIAN FEDERATION'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'RS' WHERE id_pays = 'SERBIA AND MONTENEGRO'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'PS' WHERE id_pays = 'STATE OF PALESTINE'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'SY' WHERE id_pays = 'SYRIAN ARAB REPUBLIC'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'TW' WHERE id_pays = 'TAIWAN PROVINCE OF CHINA'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'TZ' WHERE id_pays = 'TANZANIA, UNITED REPUBLIC OF'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'US' WHERE id_pays = 'UNITED STATES OF AMERICA'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'VI' WHERE id_pays = 'UNITED STATES VIRGIN ISLANDS'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'VE' WHERE id_pays = 'VENEZUELA, BOLIVARIAN REPUBLIC OF'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'VN' WHERE id_pays = 'VIET NAM'")
    cur.execute(f"UPDATE {nom} SET id_pays = 'TR' WHERE id_pays = 'TÜRKIYE'")
    cur.execute(f"DELETE FROM {nom} WHERE id_pays = 'BONAIRE' OR id_pays = 'CURAÇAO' OR id_pays = 'ESWATINI' OR id_pays = 'SINT EUSTATIUS' OR id_pays = 'SINT MAARTEN (DUTCH PART)' OR id_pays = 'SOUTH SUDAN' OR id_pays = 'SABA' OR id_pays ='TIMOR-LESTE'")
    cur.execute(f"DELETE FROM {nom} WHERE id_pays = 'TL';")

    cnx.commit()
    cnx.close()


def addCodes():
    codes = readCSV("iso")
    cnx, cur = connectSQL("projetL3")

    for i in codes:
        cur.execute(f"UPDATE pays SET A3 = '{i['A3']}', Num={i['Num']} WHERE id = '{i['A2']}'")

    cnx.commit()

    # DELETE FROM pays WHERE id = 'BV' OR id = 'AN' OR id = 'GZ' OR id = 'HM' OR id = 'SJ';

def addEmojis():
    emojis = readCSV("countries")
    cnx, cur = connectSQL("projet")

    for i in emojis:
        cur.execute(f"UPDATE pays SET emoji = '{i['emoji']}', emojiU='{i['emojiU']}' WHERE id = '{i['iso2']}'")

    cnx.commit()
    cnx.close()

def toIso2():
    cnx, cur = connectSQL("projet")
    for i in cur.execute("SELECT id, iso_3, iso_alpha FROM pays").fetchall():
        cur.execute(f"UPDATE cpi SET id_pays='{i['id']}' WHERE id_pays='{i['iso_alpha']}'")

    cnx.commit()
    # DELETE FROM gpi WHERE id_pays = 'TLS';

def filterCsv():
    liste = readCSV("SYB65_128_202209_Consumer Price Index")
    newListe = []

    for i in liste:
        if i["Series"] == "Consumer price index: General":
            newListe.append({"Pays":i["id"],"Annee":i["Year"],"Value":i["Value"]})
    
    return newListe

    

#toIso2()

#baseCsvAdd()

#baseCsv()
toSQL()
nameToCode()

#addCodes()
#addEmojis()
#toCSV()
#

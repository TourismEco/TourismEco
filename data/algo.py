import pandas as pd 
import mysql.connector



def baseCsv():
    nameSheet = [" Inbound Tourism-Arrivals","Inbound Tourism-Regions","Inbound Tourism-Regions","Inbound Tourism-Regions","Inbound Tourism-Regions","Inbound Tourism-Regions","Inbound Tourism-Regions","Inbound Tourism-Regions","Inbound Tourism-Purpose","Inbound Tourism-Purpose"]
    nameCsv = ["Arrivees","ArriveesAF","ArriveesAM","ArriveesEA","ArriveesEU","ArriveesME","ArriveesSA","ArriveesAutre","ArriveesPerso","ArriveesPro"]
    maxRow = [1341,2451,2451,2451,2451,2451,2451,2451,1120,1120]
    pad = [6,11,11,11,11,11,11,11,5,5]
    plus = [2,3,4,5,6,7,8,9,3,4]

    """nameSheet = ["Inbound Tourism-Transport","Inbound Tourism-Transport","Inbound Tourism-Transport"]
    nameCsv = ["ArriveeAvion","ArriveesEau","ArriveeTerre"]
    maxRow = [1341,1341,1341]
    pad = [6,6,6]
    plus = [3,4,5]"""



    #print(df[1995][])


    for z in range(len(nameSheet)):

        with open(f"data/csv/{nameCsv[z]}.csv","w",encoding="UTF-8") as file:

            df = pd.read_excel("data/allData.xlsx",nameSheet[z],header=5,)
            print(len(df))

            liste = [i for i in range(1995,2022)]

            file.write("Pays;1995;1996;1997;1998;1999;2000;2001;2002;2003;2004;2005;2006;2007;2008;2009;2010;2011;2012;2013;2014;2015;2016;2017;2018;2019;2020;2021\n")

            for row in range(0,maxRow[z],pad[z]):
                string = str(df["Basic data and indicators"][row])

                for col in liste:
                    if df[col][row+plus[z]] == "..":
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
    import csv

    allTable =[]
    for i in ["Arrivees","ArriveesAF","ArriveesAM","ArriveesEA","ArriveesEU","ArriveesME","ArriveesSA","ArriveesAutre","ArriveesPerso","ArriveesPro","ArriveeAvion","ArriveesEau","ArriveeTerre"]:
        
        allTable.append(deepcopy(readCSV(i)))
    
    tags = ["Arrivees","ArriveesAF","ArriveesAM","ArriveesEA","ArriveesEU","ArriveesME","ArriveesSA","ArriveesAutre","ArriveesPerso","ArriveesPro","ArriveeAvion","ArriveesEau","ArriveeTerre"]

    final = []

    for i in range(1995,2022):
        for j in range(223):
            dictA = {"Pays":allTable[0][j]["Pays"],"Annee":i}
            for z, t in enumerate(allTable):
                dictA[tags[z]] = t[j][str(i)]
            final.append(dictA.copy())
    
    return final


def toCSV():

    final = agglomerate()

    file=open("data/end.csv", "w", encoding="utf-8-sig")

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
    from sql import connectSQL

    #final = agglomerate()
    final = readCSV("paysFR")

    cnx, cur = connectSQL("projet")
    #cur.execute("CREATE TABLE IF NOT EXISTS arrivees (id INT PRIMARY KEY AUTO_INCREMENT, id_pays VARCHAR(50), annee INT, arriveesTotal INT, arriveesAF INT, arriveesAM INT, arriveesEA INT, arriveesEU INT, arriveesME INT, arriveesSA INT, arriveesAutre INT, arriveesPerso INT, arriveesPro INT, arriveesAvion INT, arriveesEau INT, arriveesTerre INT)")
    cur.execute("CREATE TABLE IF NOT EXISTS pays (id VARCHAR(3) PRIMARY KEY, lat FLOAT, lon FLOAT, nom VARCHAR(50))")

    for i,e in enumerate(final):
        ajout_value=""
        for value in e.values():
            if value == "NULL":
                ajout_value+="NULL,"
            elif type(value) == str:
                ajout_value+=f"'{value}',"
            else:
                ajout_value+=f"{value},"

        cur.execute(f"INSERT INTO pays VALUES ({ajout_value[:-1]})")

    
    cnx.commit()
    cnx.close()


def nameToCode():
    from sql import connectSQL

    codes = readCSV("paysEN")
    cnx, cur = connectSQL("projet")

    for i in codes:
        cur.execute(f"UPDATE arrivees SET id_pays = '{i['country']}' WHERE id_pays = '{i['name'].upper()}'")

    # UPDATE arrivees SET id_pays = 'BO' WHERE id_pays = 'BOLIVIA, PLURINATIONAL STATE OF'
    # UPDATE arrivees SET id_pays = 'BN' WHERE id_pays = 'BRUNEI DARUSSALAM'
    # UPDATE arrivees SET id_pays = 'CV' WHERE id_pays = 'CABO VERDE'
    # UPDATE arrivees SET id_pays = 'CG' WHERE id_pays = 'CONGO'
    # UPDATE arrivees SET id_pays = 'CD' WHERE id_pays = 'CONGO, DEMOCRATIC REPUBLIC OF THE'
    # UPDATE arrivees SET id_pays = 'CZ' WHERE id_pays = 'CZECH REPUBLIC (CZECHIA)'
    # UPDATE arrivees SET id_pays = 'HK' WHERE id_pays = 'HONG KONG, CHINA'
    # UPDATE arrivees SET id_pays = 'IR' WHERE id_pays = 'IRAN, ISLAMIC REPUBLIC OF'
    # UPDATE arrivees SET id_pays = 'KP' WHERE id_pays = 'KOREA, DEMOCRATIC PEOPLE´S REPUBLIC OF'
    # UPDATE arrivees SET id_pays = 'KR' WHERE id_pays = 'KOREA, REPUBLIC OF'
    # UPDATE arrivees SET id_pays = 'LA' WHERE id_pays = 'LAO PEOPLE´S DEMOCRATIC REPUBLIC'
    # UPDATE arrivees SET id_pays = 'MO' WHERE id_pays = 'MACAO, CHINA'
    # UPDATE arrivees SET id_pays = 'FM' WHERE id_pays = 'MICRONESIA, FEDERATED STATES OF'
    # UPDATE arrivees SET id_pays = 'MD' WHERE id_pays = 'MOLDOVA, REPUBLIC OF'
    # UPDATE arrivees SET id_pays = 'MM' WHERE id_pays = 'MYANMAR'
    # UPDATE arrivees SET id_pays = 'MK' WHERE id_pays = 'NORTH MACEDONIA'
    # UPDATE arrivees SET id_pays = 'RU' WHERE id_pays = 'RUSSIAN FEDERATION'
    # UPDATE arrivees SET id_pays = 'RS' WHERE id_pays = 'SERBIA AND MONTENEGRO'
    # UPDATE arrivees SET id_pays = 'PS' WHERE id_pays = 'STATE OF PALESTINE'
    # UPDATE arrivees SET id_pays = 'SY' WHERE id_pays = 'SYRIAN ARAB REPUBLIC'
    # UPDATE arrivees SET id_pays = 'TW' WHERE id_pays = 'TAIWAN PROVINCE OF CHINA'
    # UPDATE arrivees SET id_pays = 'TZ' WHERE id_pays = 'TANZANIA, UNITED REPUBLIC OF'
    # UPDATE arrivees SET id_pays = 'US' WHERE id_pays = 'UNITED STATES OF AMERICA'
    # UPDATE arrivees SET id_pays = 'VI' WHERE id_pays = 'UNITED STATES VIRGIN ISLANDS'
    # UPDATE arrivees SET id_pays = 'VE' WHERE id_pays = 'VENEZUELA, BOLIVARIAN REPUBLIC OF'
    # UPDATE arrivees SET id_pays = 'VN' WHERE id_pays = 'VIET NAM'
    # UPDATE arrivees SET id_pays = 'TR' WHERE id_pays = 'TÜRKIYE'
    # DELETE FROM arrivees WHERE id_pays = 'BONAIRE' OR id_pays = 'CURAÇAO' OR id_pays = 'ESWATINI' OR id_pays = 'SINT EUSTATIUS' OR id_pays = 'SINT MAARTEN (DUTCH PART)' OR id_pays = 'SOUTH SUDAN' OR id_pays = 'SABA' OR id_pays ='TIMOR-LESTE'


    cnx.commit()
    cnx.close()



toSQL()


# IDROW     PAYS    ANNEE   ARR     ARR PAR CONTINENT       RAISON ARR

# IDROW NOMPAYS CONTINENT 



# Chaque fichier
# 
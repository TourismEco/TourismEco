import pandas as pd 
from sql import connectSQL

db = "projet"

isos = ["AFG","ZAF","ALB","DZA","DEU","AND","AGO","AIA","ATA","ATG","SAU","ARG","ARM","ABW","AUS","AUT","AZE","BHS","BHR","BGD","BRB","BEL","BLZ","BMU","BTN","BLR","BOL","BIH","BWA","BRN","BRA","BGR","BFA","BDI","BEN","KHM","CMR","CAN","CPV","CHL","CHN","CXR","CYP","CCK","COL","COM","COD","COG","COK","PRK","KOR","CRI","HRV","CUB","CIV","DNK","DJI","DMA","ESP","EST","FJI","FIN","FRA","GAB","GMB","GHA","GIB","GRD","GRL","GRC","GLP","GUM","GTM","GGY","GIN","GNQ","GNB","GUY","GUF","GEO","SGS","HTI","HND","HKG","HUN","IND","IDN","IRQ","IRN","IRL","ISL","ISR","ITA","JAM","JPN","JEY","JOR","KAZ","KEN","KGZ","KIR","XKX","KWT","LAO","LSO","LVA","LBN","LBY","LBR","LIE","LTU","LUX","MAC","MKD","MDG","MYS","MWI","MDV","MLI","MLT","IMN","MNP","MAR","MTQ","MUS","MRT","MYT","MEX","FSM","MDA","MCO","MNG","MSR","MNE","MOZ","MMR","NAM","NRU","NIC","NER","NGA","NIU","NFK","NOR","NCL","NZL","NPL","OMN","UGA","UZB","PAK","PLW","PAN","PNG","PRY","NLD","PHL","PCN","POL","PYF","PRI","PRT","PER","QAT","ROU","GBR","RUS","RWA","CAF","DOM","CZE","REU","ESH","KNA","SMR","SPM","VCT","SHN","LCA","SLB","SLV","WSM","ASM","SRB","SYC","SLE","SGP","SVK","SVN","SOM","SDN","LKA","CHE","SUR","SWE","SWZ","SYR","STP","SEN","TJK","TZA","TWN","TCD","ATF","IOT","PSE","THA","TGO","TKL","TON","TTO","TUN","TKM","TUR","TUV","UKR","USA","URY","VUT","VEN","VIR","VGB","VNM","WLF","YEM","ZMB","ZWE","EGY","ARE","ECU","ERI","VAT","ETH","CYM","FLK","FRO","MHL","TCA"]

def readCSV(filename:str) -> list[dict]:
    """Cette fonction ouvre un fichier CSV et le convertit en une liste de dictionnaires, qui ont pour clé les colonnes du CSV."""
    import csv

    table = []
    file = open("data/csv/"+filename+".csv", encoding="utf-8-sig", newline="\n")
    for ligne in csv.DictReader(file,delimiter=";"):
        table.append(dict(ligne))
    file.close()

    return table


def baseCsv(nameXlsx:str,nameSheet:str,nameCsv:str,
            maxRow:int,pad:int,plus:int,head:int,
            start:int,stop:int,
            pays:str,
            checkIso=False,checkNA=False,xls=False):
    """Cette fonction traite un fichier xlsx pour le transvaser en un fichier CSV lisible. Il est nécessaire que le fichier soit formatté de telle sorte que chaque année soit en colonne, et chaque pays en ligne. Elle nécessite beaucoup de paramètres à entrer à la main :
    - `nameXlsx`, `nameSheet`, `nameCsv` : nom du fichier XLSX, nom de la feuille, et nom du CSV que l'on veut créer
    - `maxRow`, `pad`, `plus`, `head` : nombre de lignes dans le fichier, écart entre chaque pays, écart entre le nom du pays et les données, nombre de lignes d'en-tête à ignorer
    - `start` et `stop` : année de début et de fin des données
    - `pays` : nom de la colonne où est inséré le nom du pays
    - Paramètres optionnels : `checkIso` pour filtrer les pays / `checkNA` pour retirer les valeurs nulles / `xls` si le fichier est un .xls au lieu de .xlxs
    Le fichier Excel doit se trouver dans le répertoire data/xlsx, et le CSV sera sauvegardé dans data/csv"""

    with open(f"data/csv/{nameCsv}.csv","w",encoding="UTF-8") as file:

        ext = "xls" if xls else "xlsx"
        df = pd.read_excel(f"data/xlsx/{nameXlsx}.{ext}",nameSheet,header=head)
        print(len(df))

        liste = [i for i in range(start,stop+1)]
        file.write("Pays;"+";".join(list(map(lambda x:str(x),liste)))+"\n")

        for row in range(0,maxRow,pad):
            string = str(df[pays][row])

            if checkIso and string not in isos:
                continue

            for col in liste:
                if not checkNA and df[col][row+plus] == "..":
                    string+=";NULL"
                elif checkNA and pd.isna(df[col][row+plus]):
                    string+=";NULL"
                else:
                    string+=f";{df[col][row+plus]}"

            file.write(string+"\n")

    file.close()

def agglomerate(tags,start,stop,maxRow):
    from copy import deepcopy

    allTable =[]
    for i in tags:
        allTable.append(deepcopy(readCSV(i)))

    final = []

    for i in range(start,stop+1):
        for j in range(maxRow):
            dictA = {"Pays":allTable[0][j]["Pays"],"Annee":i}
            for z, t in enumerate(allTable):
                dictA[tags[z]] = t[j][str(i)]
            final.append(dictA.copy())
    
    return final

def toCSV(final,fileName):

    file = file=open(f"data/csv/{fileName}.csv", "w", encoding="utf-8-sig")

    ajout_key=""
    for key in final[0].keys():
        ajout_key=f"{key};"

    file.write(ajout_key[:-1])

    for i in range(len(final)):
        ajout_value=""
        for value in final[i].values():
            ajout_value+=f"{value};"

        file.write("\n"+ajout_value[:-1])

def toSQL(final,table,createStatement):
    global db
    cnx, cur = connectSQL(db)

    cur.execute(createStatement)
    for i,e in enumerate(final):
        ajout_value=""
        for value in e.values():
            if value == "NULL":
                ajout_value+="NULL,"
            elif type(value) == str:
                ajout_value+=f'"{value}",'
            else:
                ajout_value+=f"{value},"

        cur.execute(f"INSERT INTO {table} VALUES ({1+i},{ajout_value[:-1]})")

    cnx.commit()
    cnx.close()


def nameToCode(table):
    global db
    codes = readCSV("paysEN")
    cnx, cur = connectSQL(db)

    for i in codes:
        cur.execute(f"UPDATE {table} SET id_pays = '{i['country']}' WHERE id_pays = '{i['name'].upper()}'")

    cur.execute(f"UPDATE {table} SET id_pays = 'BO' WHERE id_pays = 'BOLIVIA, PLURINATIONAL STATE OF'")
    cur.execute(f"UPDATE {table} SET id_pays = 'BN' WHERE id_pays = 'BRUNEI DARUSSALAM'")
    cur.execute(f"UPDATE {table} SET id_pays = 'CV' WHERE id_pays = 'CABO VERDE'")
    cur.execute(f"UPDATE {table} SET id_pays = 'CG' WHERE id_pays = 'CONGO'")
    cur.execute(f"UPDATE {table} SET id_pays = 'CD' WHERE id_pays = 'CONGO, DEMOCRATIC REPUBLIC OF THE'")
    cur.execute(f"UPDATE {table} SET id_pays = 'CZ' WHERE id_pays = 'CZECH REPUBLIC (CZECHIA)'")
    cur.execute(f"UPDATE {table} SET id_pays = 'HK' WHERE id_pays = 'HONG KONG, CHINA'")
    cur.execute(f"UPDATE {table} SET id_pays = 'IR' WHERE id_pays = 'IRAN, ISLAMIC REPUBLIC OF'")
    cur.execute(f"UPDATE {table} SET id_pays = 'KP' WHERE id_pays = 'KOREA, DEMOCRATIC PEOPLE´S REPUBLIC OF'")
    cur.execute(f"UPDATE {table} SET id_pays = 'KR' WHERE id_pays = 'KOREA, REPUBLIC OF'")
    cur.execute(f"UPDATE {table} SET id_pays = 'LA' WHERE id_pays = 'LAO PEOPLE´S DEMOCRATIC REPUBLIC'")
    cur.execute(f"UPDATE {table} SET id_pays = 'MO' WHERE id_pays = 'MACAO, CHINA'")
    cur.execute(f"UPDATE {table} SET id_pays = 'FM' WHERE id_pays = 'MICRONESIA, FEDERATED STATES OF'")
    cur.execute(f"UPDATE {table} SET id_pays = 'MD' WHERE id_pays = 'MOLDOVA, REPUBLIC OF'")
    cur.execute(f"UPDATE {table} SET id_pays = 'MM' WHERE id_pays = 'MYANMAR'")
    cur.execute(f"UPDATE {table} SET id_pays = 'MK' WHERE id_pays = 'NORTH MACEDONIA'")
    cur.execute(f"UPDATE {table} SET id_pays = 'RU' WHERE id_pays = 'RUSSIAN FEDERATION'")
    cur.execute(f"UPDATE {table} SET id_pays = 'RS' WHERE id_pays = 'SERBIA AND MONTENEGRO'")
    cur.execute(f"UPDATE {table} SET id_pays = 'PS' WHERE id_pays = 'STATE OF PALESTINE'")
    cur.execute(f"UPDATE {table} SET id_pays = 'SY' WHERE id_pays = 'SYRIAN ARAB REPUBLIC'")
    cur.execute(f"UPDATE {table} SET id_pays = 'TW' WHERE id_pays = 'TAIWAN PROVINCE OF CHINA'")
    cur.execute(f"UPDATE {table} SET id_pays = 'TZ' WHERE id_pays = 'TANZANIA, UNITED REPUBLIC OF'")
    cur.execute(f"UPDATE {table} SET id_pays = 'US' WHERE id_pays = 'UNITED STATES OF AMERICA'")
    cur.execute(f"UPDATE {table} SET id_pays = 'VI' WHERE id_pays = 'UNITED STATES VIRGIN ISLANDS'")
    cur.execute(f"UPDATE {table} SET id_pays = 'VE' WHERE id_pays = 'VENEZUELA, BOLIVARIAN REPUBLIC OF'")
    cur.execute(f"UPDATE {table} SET id_pays = 'VN' WHERE id_pays = 'VIET NAM'")
    cur.execute(f"UPDATE {table} SET id_pays = 'TR' WHERE id_pays = 'TÜRKIYE'")
    cur.execute(f"DELETE FROM {table} WHERE id_pays = 'BONAIRE' OR id_pays = 'CURAÇAO' OR id_pays = 'ESWATINI' OR id_pays = 'SINT EUSTATIUS' OR id_pays = 'SINT MAARTEN (DUTCH PART)' OR id_pays = 'SOUTH SUDAN' OR id_pays = 'SABA' OR id_pays ='TIMOR-LESTE'")
    cur.execute(f"DELETE FROM {table} WHERE id_pays = 'TL';")

    cnx.commit()
    cnx.close()

def toIso2():
    global db
    cnx, cur = connectSQL(db)

    for i in cur.execute("SELECT id, iso_3, iso_alpha FROM pays").fetchall():
        cur.execute(f"UPDATE ecologie SET id_pays='{i['id']}' WHERE id_pays='{i['iso_3']}'")

    cnx.commit()
    # DELETE FROM gpi WHERE id_pays = 'TLS';


def checking():
    global db
    cnx, cur = connectSQL(db)
    cur.execute("DROP TABLE IF EXISTS checking;")

    pays = ["AD","AE","AF","AG","AI","AL","AM","AO","AQ","AR","AS","AT","AU","AW","AX","AZ","BA","BB","BD","BE","BF","BG","BH","BI","BJ","BL","BM","BN","BO","BQ","BR","BS","BT","BV","BW","BY","BZ","CA","CC","CD","CF","CG","CH","CI","CK","CL","CM","CN","CO","CR","CU","CV","CW","CX","CY","CZ","DE","DJ","DK","DM","DO","DZ","EC","EE","EG","EH","ER","ES","ET","FI","FJ","FK","FM","FO","FR","GA","GB","GD","GE","GF","GG","GH","GI","GL","GM","GN","GP","GQ","GR","GS","GT","GU","GW","GY","HK","HM","HN","HR","HT","HU","ID","IE","IL","IM","IN","IO","IQ","IR","IS","IT","JE","JM","JO","JP","KE","KG","KH","KI","KM","KN","KP","KR","KW","KY","KZ","LA","LB","LC","LI","LK","LR","LS","LT","LU","LV","LY","MA","MC","MD","ME","MF","MG","MH","MK","ML","MM","MN","MO","MP","MQ","MR","MS","MT","MU","MV","MW","MX","MY","MZ","NA","NC","NE","NF","NG","NI","NL","NO","NP","NR","NU","NZ","OM","PA","PE","PF","PG","PH","PK","PL","PM","PN","PR","PS","PT","PW","PY","QA","RE","RO","RS","RU","RW","SA","SB","SC","SD","SE","SG","SH","SI","SJ","SK","SL","SM","SN","SO","SR","SS","ST","SV","SX","SY","SZ","TC","TD","TF","TG","TH","TJ","TK","TL","TM","TN","TO","TR","TT","TV","TW","TZ","UA","UG","UM","US","UY","UZ","VA","VC","VE","VG","VI","VN","VU","WF","WS","YE","YT","ZA","ZM","ZW"] # pays listés sur AMCharts

    liste = []
    print(len(pays))
    for i in pays:
        a = {"Code":i}
        
        if cur.execute(f"SELECT DISTINCT id FROM pays WHERE id = '{i}'").fetchone() != None:
            a["pays"] = True
        else:
            a["pays"] = False

        for j in ["arrivees","cpi","ecologie","gpi","pib","villes"]:
            if cur.execute(f"SELECT DISTINCT id_pays FROM {j} WHERE id_pays = '{i}'").fetchone() != None:
                a[j] = True
            else:
                a[j] = False
        liste.append(a.copy())
    
    cur.execute("CREATE TABLE IF NOT EXISTS checking (id VARCHAR(3) PRIMARY KEY, pays BOOLEAN, arrivees BOOLEAN, cpi BOOLEAN, ecologie BOOLEAN, gpi BOOLEAN, pib BOOLEAN, villes BOOLEAN)")
    for i in liste:
        cur.execute(f"INSERT INTO checking VALUES ('{i['Code']}',{i['pays']},{i['arrivees']},{i['cpi']},{i['ecologie']},{i['gpi']},{i['pib']},{i['villes']})")
    
    cnx.commit()

def clearTables(liste):
    global db
    cnx, cur = connectSQL(db)

    tables = ["arrivees","departs","cpi","gpi","emploi","argent","ecologie","pib","villes"]
    for i in liste:
        for j in tables:
            cur.execute(f"DELETE FROM {j} WHERE id_pays = '{i}'")
        cur.execute(f"DELETE FROM pays WHERE id = '{i}'")
    cnx.commit()

if __name__ == "__main__":
    pass

# baseCsv("allData"," Inbound Tourism-Arrivals","test",1341,6,2,5,1995,2021,"Basic data and indicators")
# checking()
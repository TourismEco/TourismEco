# Implémentation personnalisée des classes curseur et réponses du connecteur MySQL.
# Tout a été écrit par moi-même pour un projet antérieur, et réutilisé ici.
# NE RIEN TOUCHER PAR PITIE, JE SAIS OU VOUS HABITEZ!!!!!!!!

import mysql.connector # version 8.0.30

def connectSQL(database:str):
    """Se connecte à une base de donnée MySQL, et renvoie le curseur et la connexion associée."""
    try:
        cnx = mysql.connector.connect(user='root', password='root',
                                host='localhost',
                                database=database,autocommit=False)
        cursor = CustomCursor(cnx)
    except:
        cnx = mysql.connector.connect(user='root', password='root',
                                host='localhost')
        cursor = CustomCursor(cnx)
        cursor.execute(f"CREATE DATABASE {database}")
        cnx.commit()
        cnx,cursor=connectSQL(database) # appel récursif une fois que la base est créée
    return cnx, cursor


class MySQLResponse():
    """Permet de traiter les requêtes à la base sous forme d'une classe objet n'ayant qu'un attribut : fetch.
    Imite basiquement le comportement de la bibliothèque sqlite3."""
    def __init__(self,cursor):
        keywords=cursor.column_names
        liste=[]
        try:
            for i in cursor:
                temp=list(map(lambda x:x, i))
                liste.append({keywords[i]:temp[i] for i in range(len(keywords))})
        except:
            pass
        self.fetch=liste
    
    def fetchone(self):
        if len(self.fetch)==0:
            return None
        return self.fetch[0]
    
    def fetchall(self):
        return self.fetch


class CustomCursor(mysql.connector.cursor_cext.CMySQLCursorBuffered):
    """Curseur personnalisé."""
    def __init__(self,connection):
        super().__init__(connection)

    def execute(self,operation) -> MySQLResponse:
        super().execute(operation)
        return MySQLResponse(self)
    
    def execval(self,operation,val) -> MySQLResponse:
        super().execute(operation,val)
        return MySQLResponse(self)
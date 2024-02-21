from textes import algo

## Fonction pour la tendance à la hausse ou à la baisse
def tendance(liste):
    if not liste:
        return "Il n'y a aucune donnée disponible pour analyser la tendance."

    type = "à la hausse" if liste[-1][1] == 1 else "à la baisse"  # Je détermine le type de tendance "hausse" ou "baisse"
    index = 0  # J'initialise l'indice de début/fin de la tendance
    years = 0  # Je commence avec un nombre d'années de tendance de 0

    # Je cherche l'indice de début de la tendance et le nombre d'années de tendance
    for i, (nb_years, _, _, _, _) in enumerate(liste):
        if nb_years >= 3:  # Si le nombre d'années de la variation est au moins 3
            index = i
            years = nb_years  # Je mets à jour le nombre d'années de tendance

    index_end = index + years - 1
    index_end = min(index_end, len(liste) - 1)  # Je m'assure que l'indice de fin ne dépasse pas la longueur de liste

    # Je retourne le nombre d'années, le type de tendance, et les valeurs pour cette tendance en utilisant la fonction "algo" du fichier textes.py
    return f"Depuis {years} années, il y a une tendance {type} (de {liste[index][2]} à {liste[index_end][3]}).\n"


## Fonction pour la forte croissance/décroissance puis décroissance/croissance
def detecter_pattern(liste):
    if not liste:
        return "Aucune donnée disponible pour analyser le pattern."

    # Je parcours les variations dans la liste
    for i in range(len(liste) - 1):
        # Je vérifie si la variation i est une forte croissance suivie d'une forte décroissance en comparant le pourcentage de variation avec 50
        if liste[i][1] == 1 and liste[i+1][1] == -1 and liste[i][4] > 50 and liste[i+1][4] < -50:
            return "Il y a une forte croissance suivie d'une forte décroissance."

        # Je vérifie si la variation i est une forte décroissance suivie d'une forte croissance en comparant le pourcentage de variation avec 50
        elif liste[i][1] == -1 and liste[i+1][1] == 1 and liste[i][4] < -50 and liste[i+1][4] > 50:
            return "Il y a une forte décroissance suivie d'une forte croissance."

    return "Il n'y a pas de forte croissance suivie d'une forte décroissance, ou de forte décroissance suivie d'une forte croissance.\n"


##Fonction pour le chaos
def identifier_chaos(liste):
    if not liste:
        return "Aucune donnée disponible pour identifier le chaos."

    # Je parcours les variations dans la liste
    for nb_year, sens, start_year, end_year, ecart in liste:
        # Je vérifie si l'écart entre les valeurs est très élevé. Je prend donc la valeur absolue de l'écart pour plus de simplicité
        if abs(ecart) > 100:  # Je fixe un seuil à 100 pour le pourcentage de variation
            return "Il y a des fluctuations extrêmes dans les données, indiquant un chaos total."

    return "On ne peut pas identifié de chaos total dans les données.\n"


###### Exemple d'utilisation des fonctions 

# Fonction tendance:
data = [18, 15, 13, 12, 10, 14, 18, 19, 21, 28]
liste, _ = algo(data)
print("\nDonnées:", data)
print("Variations:", liste)
print("Tendance:", tendance(liste))

data_baisse = [30, 27, 29, 12, 17, 12, 10, 8, 6, 2]
liste_baisse, _ = algo(data_baisse)
print("\nDonnées:", data_baisse)
print("Variations:", liste_baisse)
print("Tendance:", tendance(liste_baisse))

#Fonction patern
data_patern = [10, 15, 20, 25, 30, 25, 20, 15, 10]
liste, _ = algo(data_patern)
print("\nDonnées:", data_patern)
print("Variations:", liste)
print("Pattern:", detecter_pattern(liste))

data_patern_dec = [30, 25, 20, 15, 10, 12, 15, 20, 25, 30]
liste_data_patern_dec, _ = algo(data_patern_dec)
print("\nDonnées:", data_patern_dec)
print("Variations:", liste_data_patern_dec)
print("Pattern:", detecter_pattern(liste_data_patern_dec))

data = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100]
liste, _ = algo(data)
print("Données:", data)
print("Variations:", liste)
print("Pattern:", detecter_pattern(liste))


#Chaos
data_chaos = [10, 30, 15, 25, 40, 12, 35, 18, 32, 20, 38]
liste_chaos, _ = algo(data_chaos)
print("\nDonnées:", data_chaos)
print("Variations:", liste_chaos)
print("Chaos:", identifier_chaos(liste_chaos))

data_stable = [10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]
liste_stable, _ = algo(data_stable)
print("\nDonnées:", data_stable)
print("Variations:", liste_stable)
print("Chaos:", identifier_chaos(liste_stable))

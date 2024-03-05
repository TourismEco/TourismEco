def constance(liste):
    ### Vérifie la constance stricte
    if len(liste) == 1:
        # croissance constante
        if liste[0][1] == 1:
            return ""
        
        # décroissance constante 
        elif liste[0] == -1:
            return ""
        
        # stagnation parfaite
        else:
            return ""

    return False

def big(liste, vals):
    ### Vérifie si une partie majeure (75%) est constante en croissance
    maxNb = max(liste, key=lambda x:x[0])
    if maxNb[0] > len(vals)*0.75:
        ind = vals.index(maxNb) # à optimiser

        # grosse constante au début
        if ind == 0:
            const = constance(liste[1:]) # analyse la constance sur les autres éléments
            return ""
            
        # grosse constante ces dernières années
        elif ind == len(vals)-1:
            const = constance(liste[:-1]) # analyse la constance sur les autres éléments
            return ""
        
        # grosse constante au milieu
        else:
            return ""
    else:
        return False

def switch(liste, vals):
    if len(liste) == 2:
        # La première partie est faible
        if liste[0][0] < len(liste)*0.2:
            return constance(liste[0:])
        
        # La deuxième partie est faible,
        elif liste[1][0] < len(liste)*0.2:
            return constance(liste[:-1])
        
        # Les deux parties ne sont pas équivalentes, mais toutes les deux assez importantes
        else:
            return ""

def fluctu(liste, vals):
    # Changement de variation 75% du temps
    if (len(liste) > len(vals)*0.75):
        return ""
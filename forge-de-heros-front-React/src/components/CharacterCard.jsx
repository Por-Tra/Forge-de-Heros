//import { getCharacter } from "../utils/api";
import { Link } from "react-router-dom"; // ajouté

function CharacterCard({ characterID })
{
    // const data = getCharacter(characterID);

    /* TODO
      Informations a afficher:
        - Avatar
        - Nom
        - Classe
        - Race
        - Niveau 

        -Tri par noms et par niveau
        -Filtre par nom, classe et race
     */

    return (
        <div>
            {/* Affichage des information du personnages */}
            <h2>CacaMan</h2> 
            <img src="" alt={`Avatar de #${characterID}`}></img> 
            <p>
                Classe: PipiChaud <br/>
                Race: DIEU <br/>
                Niveau: 67
            </p>

            <Link to={`/characters/${characterID}`}>
                Plus d'informations
            </Link>
        </div>
    );
}

export default CharacterCard;
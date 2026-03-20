import { useParams } from "react-router-dom";
//import { getCharacter } from "../utils/api";

function CharacterDetail()
{
    const { id } = useParams();
    //const data = getCharacter(id);

    /* TODO
        - Afficher toutes les informations du personnages:
            - Nom
            - Avatar
            - Stats
            - Classe
            - Race
            - Compétences
        
        - Affichage visuel des stats (barre de progression, radar chart, ou autre)
        - Afficher les groupes auxquels le personnage appartient (CLIQUABLES)
    */

    const characters = [
        {
            id: 1,
            avatar: null,
            name: "Jesus",
            class: "Dieu",
            race: "Humain",
            level: 67
        }, 
        {
            id: 2,
            avatar: null,
            name: "Simple Humain",
            class: "nullos",
            race: "Humain",
            level: 1
        }, 
        {
            id: 3,
            avatar: null,
            name: "gros caca",
            class: "caca",
            race: "Dieu",
            level: 5959
        }, 
        {
            id: 4,
            avatar: null,
            name: "AAAAAAAAAAA",
            class: "caca",
            race: "Dieu",
            level: 6
        },
    ]
    
    const characterChoosed = characters.find((character) => character.id === Number(id));

    return (
        <>
            <h1>{characterChoosed ? characterChoosed.name : "Personnage introuvable"}</h1>
        </>
    )
}

export default CharacterDetail;
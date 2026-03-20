import CharacterCard from "../components/CharacterCard"

function Characters()
{
    /*
        -Tri par noms et par niveau
        -Filtre par nom, classe et race
    */

    return (
        <>
            <h1>Affichage des personnages</h1>

            <div>

                <CharacterCard characterID="3" />
                <CharacterCard characterID="3" />
                <CharacterCard characterID="3" />

            </div>
        </>
    )
}

export default Characters;
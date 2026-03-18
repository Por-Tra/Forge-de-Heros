import CharacterCard from "../components/CharacterCard"
import Header from "../components/Header";


function Characters()
{
    return (
        <>
            <Header/>
            <h1>Affichage des personnages</h1>

            <div>

                <CharacterCard characterID="3" />
                <CharacterCard characterID="5" />
            </div>
        </>
    )
}

export default Characters;
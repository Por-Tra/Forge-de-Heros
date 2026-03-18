import { Link } from "react-router-dom";

function Home()
{
    return (
        <div>
            <h2>Bienvenue sur la page d'accueil</h2>

            <Link to="/characters">
                Liste Personnages
            </Link> <br/>
            <Link to="/parties">
                Liste Groupes
            </Link>
        </div>
    )
}

export default Home;
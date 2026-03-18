import { Link } from "react-router-dom";
import Header from "../components/Header";


function Home()
{
    return (
        <div>
            <Header/>

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
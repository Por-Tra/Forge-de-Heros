import { Link } from "react-router-dom";

function NavBar(){

    return(
        <div className="navbar">
            <Link to="/" className="btnNavbar">
                Accueil
            </Link>

            <Link to="/characters" className="btnNavbar">
                Personnage
            </Link>

            <Link to="/parties" className="btnNavbar">
                Groupe
            </Link>
        </div>
    )

}

export default NavBar
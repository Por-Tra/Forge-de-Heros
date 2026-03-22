import { Link } from 'react-router-dom';
import '../styles/NavBar.scss';

function NavBar() {
  return (
    <nav className="navbar">
      <Link to="/" className="btnNavbar">
        Accueil
      </Link>

      <Link to="/characters" className="btnNavbar">
        Personnages
      </Link>

      <Link to="/parties" className="btnNavbar">
        Groupes
      </Link>
    </nav>
  );
}

export default NavBar;

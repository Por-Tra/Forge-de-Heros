import NavBar from './NavBar';
import '../styles/Header.scss';

function Header() {
  return (
    <header className="header">
      <h1 className="header__title">Forge de Héros</h1>
      <NavBar />
    </header>
  );
}

export default Header;

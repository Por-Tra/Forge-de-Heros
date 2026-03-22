import { Link } from 'react-router-dom';
import Header from '../components/Header';
import '../styles/Home.scss';

function Home() {
  return (
    <>
      <Header />
      <div className="home-page">
        <section className="home-hero">
          <h2 className="home-hero__title">Bienvenue à la Forge de Héros</h2>
          <p className="home-hero__subtitle">
            Créez vos personnages légendaires et formez des groupes
            d'aventuriers
          </p>
        </section>

        <section className="home-content">
          <div className="home-cards">
            <Link to="/characters" className="home-card">
              <div className="home-card__icon">⚔</div>
              <h3 className="home-card__title">Personnages</h3>
              <p className="home-card__description">
                Explorez la liste de tous les héros et leurs caractéristiques
                uniques
              </p>
            </Link>

            <Link to="/parties" className="home-card">
              <div className="home-card__icon">🛡</div>
              <h3 className="home-card__title">Groupes d'Aventure</h3>
              <p className="home-card__description">
                Découvrez les différents groupes et leurs membres intrépides
              </p>
            </Link>
          </div>

          <div className="home-features">
            <div className="home-feature">
              <span className="home-feature__icon">📊</span>
              <p>Consultez les statistiques détaillées de chaque personnage</p>
            </div>
            <div className="home-feature">
              <span className="home-feature__icon">🎯</span>
              <p>Filtrez et triez les personnages selon vos critères</p>
            </div>
            <div className="home-feature">
              <span className="home-feature__icon">👥</span>
              <p>Explorez les groupes et leurs compositions</p>
            </div>
          </div>
        </section>
      </div>
    </>
  );
}

export default Home;

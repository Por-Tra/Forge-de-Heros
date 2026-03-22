import { Link } from 'react-router-dom';
import '../styles/Character.Card.scss';
import { getCharacter } from '../utils/api';

function CharacterCard({ characterID }) {
  // Initiales pour avatar placeholder
  const data = getCharacter(characterID);
  const getInitials = (name = '') =>
    name
      .split(' ')
      .filter(Boolean)
      .map((n) => n[0])
      .slice(0, 2)
      .join('')
      .toUpperCase();

  return (
    <div className="character-card">
      <div className="character-card__header">
        {data.image ? (
          <img
            className="character-card__avatar"
            src={data.image}
            alt={`Avatar de ${data.name}`}
          />
        ) : (
          <div className="character-card__avatar character-card__avatar--placeholder">
            {getInitials(data.name)}
          </div>
        )}
        <div>
          <div className="character-card__name">{data.name}</div>
          <div className="character-card__rune" aria-hidden="true"></div>
        </div>
      </div>

      <div className="character-card__body">
        <div className="character-card__stats">
          <div className="character-card__stat">
            <span>Classe</span>
            <span className="value">
              {data.characterClass?.name || 'Inconnue'}
            </span>
          </div>
          <div className="character-card__stat">
            <span>Race</span>
            <span className="value">{data.race?.name || 'Inconnue'}</span>
          </div>
          <div className="character-card__stat">
            <span>Niveau</span>
            <span className="value">{data.level}</span>
          </div>
        </div>

        <Link
          className="character-card__link"
          to={`/characters/${characterID}`}
        >
          Plus d'informations
        </Link>
      </div>
    </div>
  );
}

export default CharacterCard;

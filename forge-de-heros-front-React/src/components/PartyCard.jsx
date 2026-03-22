import { Link } from 'react-router-dom';
import '../styles/PartyCard.scss';

function PartyCard({ partyID, data }) {
  const currentMembers = data.members?.length || 0;
  const maxSize = data.maxSize || 0;
  const remainingSpots = maxSize - currentMembers;
  const isFull = remainingSpots <= 0;

  return (
    <div className={`party-card ${isFull ? 'party-card--full' : ''}`}>
      <div className="party-card__header">
        <div className="party-card__icon" aria-hidden="true">
          {isFull ? '🔒' : '⚔'}
        </div>
        <div>
          <div className="party-card__name">{data.name}</div>
          <div className="party-card__rune" aria-hidden="true"></div>
        </div>
      </div>

      <div className="party-card__body">
        <div className="party-card__stats">
          <div className="party-card__stat">
            <span>Membres</span>
            <span className="value">
              {currentMembers} / {maxSize}
            </span>
          </div>
          <div className="party-card__stat">
            <span>Places restantes</span>
            <span
              className={`value ${isFull ? 'value--full' : 'value--available'}`}
            >
              {remainingSpots}
            </span>
          </div>
        </div>

        <Link className="party-card__link" to={`/parties/${partyID}`}>
          Plus d'informations
        </Link>
      </div>
    </div>
  );
}

export default PartyCard;

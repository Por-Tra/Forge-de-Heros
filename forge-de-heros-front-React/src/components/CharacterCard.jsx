import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import '../styles/Character.Card.scss';
import { getCharacter } from '../utils/api';

function CharacterCard({ characterID }) {
  const [data, setData] = useState(null);

  useEffect(() => {
    let mounted = true;

    const normalizeCharacter = (src) => {
      const item = src?.data ?? src ?? {};
      return {
        ...item,
        characterClass: item.characterClass ?? item.class ?? null,
        strength: item.stats?.strength ?? item.strength ?? 0,
        dexterity: item.stats?.dexterity ?? item.dexterity ?? 0,
        constitution: item.stats?.constitution ?? item.constitution ?? 0,
        intelligence: item.stats?.intelligence ?? item.intelligence ?? 0,
        wisdom: item.stats?.wisdom ?? item.wisdom ?? 0,
        charisma: item.stats?.charisma ?? item.charisma ?? 0,
        skills: item.skills ?? [],
        parties: item.parties ?? [],
      };
    };

    (async () => {
      try {
        const res = await getCharacter(characterID);
        if (!mounted) return;
        setData(normalizeCharacter(res));
      } catch (err) {
        if (mounted) {
          setData({
            id: characterID,
            name: 'Inconnu',
            characterClass: null,
            race: null,
            level: '?',
            image: null,
          });

          return (<p>Erreur: {err.message}</p>)
        }
      }
    })();

    return () => {
      mounted = false;
    };
  }, [characterID]);

  const getInitials = (name = '') =>
    name
      .split(' ')
      .filter(Boolean)
      .map((n) => n[0])
      .slice(0, 2)
      .join('')
      .toUpperCase();

  if (!data) {
    return (
      <div className="character-card character-card--loading">
        <div className="character-card__header">
          <div className="character-card__avatar character-card__avatar--placeholder" />
          <div>
            <div className="character-card__name">Chargement...</div>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="character-card">
      <div className="character-card__header">
        {data.image ? (
          <img
            className="character-card__avatar"
            src={`https://127.0.0.1:8000/uploads/avatars/${data.image}`}
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

        <Link className="character-card__link" to={`/characters/${characterID}`}>
          Plus d'informations
        </Link>
      </div>
    </div>
  );
}

export default CharacterCard;

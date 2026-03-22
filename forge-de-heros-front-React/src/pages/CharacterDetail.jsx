import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { getCharacter } from '../utils/api';
import Header from '../components/Header';
import '../styles/CharacterDetail.scss';

function CharacterDetail() {
  const { id } = useParams();
  const [character, setCharacter] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchCharacter = async () => {
      try {
        setLoading(true);
        setError(null);
        const data = await getCharacter(id);
        setCharacter(data);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchCharacter();
  }, [id]);

  if (loading) {
    return (
      <>
        <Header />
        <div className="character-detail-page">
          <p className="cd-loading">Chargement du personnage...</p>
        </div>
      </>
    );
  }

  if (error || !character) {
    return (
      <>
        <Header />
        <div className="character-detail-page">
          <p className="cd-error">{error || 'Personnage introuvable.'}</p>
          <Link to="/characters" className="cd-back-link">
            ← Liste des personnages
          </Link>
        </div>
      </>
    );
  }

  // Stats scale: min 8, max 15
  const STAT_MIN = 8;
  const STAT_MAX = 15;
  const stats = {
    Force: character.strength,
    Dextérité: character.dexterity,
    Constitution: character.constitution,
    Intelligence: character.intelligence,
    Sagesse: character.wisdom,
    Charisme: character.charisma,
  };
  const statEntries = Object.entries(stats);

  const clamp = (v, a, b) => Math.max(a, Math.min(b, v));
  const valueToPercent = (v) => {
    const clamped = clamp(v, STAT_MIN, STAT_MAX);
    return Math.round(((clamped - STAT_MIN) / (STAT_MAX - STAT_MIN)) * 100);
  };

  // map ability codes to stat labels
  const ABILITY_MAP = {
    STR: 'Force',
    DEX: 'Dextérité',
    CON: 'Constitution',
    INT: 'Intelligence',
    WIS: 'Sagesse',
    CHA: 'Charisme',
  };

  // initials fallback for avatar
  const getInitials = (name = '') =>
    name
      .split(' ')
      .filter(Boolean)
      .map((n) => n[0])
      .slice(0, 2)
      .join('')
      .toUpperCase();

  const skills = character.skills || [];
  const parties = character.parties || [];

  return (
    <>
      <Header />
      <div className="character-detail-page">
        <h1 className="cd-title">{character.name}</h1>

        <div className="cd-top">
          {character.image ? (
            <img
              className="cd-avatar"
              src={character.image}
              alt={`Avatar de ${character.name}`}
            />
          ) : (
            <div
              className="cd-avatar cd-avatar--placeholder"
              aria-hidden="true"
            >
              {getInitials(character.name)}
            </div>
          )}

          <Link to="/characters" className="cd-back-link">
            ← Liste des personnages
          </Link>

          <div className="cd-meta">
            <p className="cd-meta-line">
              <span className="cd-meta-label">Classe:</span>
              <strong>{character.characterClass?.name}</strong>
            </p>
            {character.characterClass?.description && (
              <p className="cd-meta-desc">
                {character.characterClass.description}
              </p>
            )}

            <p className="cd-meta-line">
              <span className="cd-meta-label">Race:</span>
              <strong>{character.race?.name}</strong>
            </p>
            {character.race?.description && (
              <p className="cd-meta-desc">{character.race.description}</p>
            )}

            <p className="cd-meta-line">
              <span className="cd-meta-label">Niveau:</span>
              <strong>{character.level}</strong>
              <span className="cd-meta-sep"> — </span>
              <span className="cd-meta-label">Vie:</span>
              <strong>{character.healthPoints}</strong>
            </p>
          </div>
        </div>

        <section className="cd-stats">
          <h2>Stats</h2>
          <div className="cd-stats-list">
            {statEntries.map(([label, rawVal]) => {
              const originalVal =
                typeof rawVal === 'number' ? rawVal : Number(rawVal) || 0;
              const clampedVal = clamp(originalVal, STAT_MIN, STAT_MAX);
              const pct = valueToPercent(originalVal);
              const titleExtra =
                originalVal !== clampedVal
                  ? `Valeur originale: ${originalVal}`
                  : null;

              return (
                <div
                  className="cd-stat-row"
                  key={label}
                  title={titleExtra || undefined}
                >
                  <div className="cd-stat-label">
                    <span className="cd-stat-name">{label}</span>
                    <span className="cd-stat-value">{clampedVal}</span>
                  </div>

                  <div
                    className="cd-stat-track"
                    role="progressbar"
                    aria-valuemin={STAT_MIN}
                    aria-valuemax={STAT_MAX}
                    aria-valuenow={clampedVal}
                  >
                    <div
                      className="cd-stat-fill"
                      style={{ width: `${pct}%` }}
                    />
                  </div>

                  <div className="cd-stat-scale">
                    <small>{STAT_MIN}</small>
                    <small>{STAT_MAX}</small>
                  </div>
                </div>
              );
            })}
          </div>
          <p className="cd-note">
            Échelle : {STAT_MIN} — {STAT_MAX}. Les valeurs affichées sont
            ramenées dans cet intervalle (la valeur originale est visible au
            survol si différente).
          </p>
        </section>

        <section className="cd-skills" aria-labelledby="skills-title">
          <h2 id="skills-title">Compétences</h2>
          {skills.length === 0 ? (
            <p className="cd-muted">Aucune compétence définie.</p>
          ) : (
            <div className="cd-skills-list">
              {skills.map((s) => (
                <div className="cd-skill-row" key={s.id}>
                  <div className="cd-skill-name">{s.name}</div>
                  <div className="cd-skill-abilities">
                    <span
                      className="cd-ability-badge"
                      title={ABILITY_MAP[s.ability] || s.ability}
                    >
                      {ABILITY_MAP[s.ability] || s.ability}
                    </span>
                  </div>
                </div>
              ))}
            </div>
          )}
        </section>

        <section className="cd-parties" aria-labelledby="groups-title">
          <h2 id="groups-title">Groupes</h2>
          {parties.length === 0 ? (
            <p className="cd-muted">Aucun groupe.</p>
          ) : (
            <div className="cd-parties-list">
              {parties.map((party) => (
                <Link
                  key={party.id}
                  to={`/parties/${party.id}`}
                  className="cd-party-badge"
                >
                  {party.name}
                </Link>
              ))}
            </div>
          )}
        </section>
      </div>
    </>
  );
}

export default CharacterDetail;

import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { getParty } from '../utils/api';
import Header from '../components/Header';
import '../styles/PartyDetail.scss';

function PartyDetail() {
  const { id } = useParams();
  const [party, setParty] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchParty = async () => {
      try {
        setLoading(true);
        setError(null);
        const data = await getParty(id);
        setParty(data);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchParty();
  }, [id]);

  if (loading) {
    return (
      <>
        <Header />
        <div className="party-detail-page">
          <p className="pd-loading">Chargement du groupe...</p>
        </div>
      </>
    );
  }

  if (error || !party) {
    return (
      <>
        <Header />
        <div className="party-detail-page">
          <p className="pd-error">{error || 'Groupe introuvable.'}</p>
          <Link to="/parties" className="pd-back-link">
            ← Retour à la liste des groupes
          </Link>
        </div>
      </>
    );
  }

  const currentMembers = party.members?.length || 0;
  const maxSize = party.maxSize || 0;
  const remainingSpots = maxSize - currentMembers;
  const isFull = remainingSpots <= 0;

  return (
    <>
      <Header />
      <div className="party-detail-page">
        <h1 className="pd-title">{party.name}</h1>

      <Link to="/parties" className="pd-back-link">
        ← Liste des groupes
      </Link>

      <div className="pd-top">
        <div className="pd-icon-wrapper" aria-hidden="true">
          <div className={`pd-icon ${isFull ? 'pd-icon--full' : ''}`}>
            {isFull ? '🔒' : '⚔'}
          </div>
        </div>

        <div className="pd-meta">
          <p className="pd-meta-line">
            <span className="pd-meta-label">Description:</span>
          </p>
          <p className="pd-description">
            {party.description || 'Aucune description disponible.'}
          </p>

          <p className="pd-meta-line">
            <span className="pd-meta-label">Taille du groupe:</span>
            <strong>
              {currentMembers} / {maxSize}
            </strong>
          </p>

          <p className="pd-meta-line">
            <span className="pd-meta-label">Places restantes:</span>
            <strong className={isFull ? 'pd-full' : 'pd-available'}>
              {remainingSpots}
            </strong>
          </p>

          <p className="pd-meta-line">
            <span className="pd-meta-label">Statut:</span>
            <strong className={isFull ? 'pd-full' : 'pd-available'}>
              {isFull ? 'Complet' : 'Places disponibles'}
            </strong>
          </p>
        </div>
      </div>

      <section className="pd-members" aria-labelledby="members-title">
        <h2 id="members-title">Membres du groupe</h2>
        {currentMembers === 0 ? (
          <p className="pd-muted">Aucun membre dans ce groupe.</p>
        ) : (
          <div className="pd-members-list">
            {party.members.map((member) => (
              <Link
                key={member.id}
                to={`/characters/${member.id}`}
                className="pd-member-card"
              >
                <div className="pd-member-avatar">
                  {member.image ? (
                    <img src={member.image} alt={`Avatar de ${member.name}`} />
                  ) : (
                    <div className="pd-member-placeholder">
                      {member.name
                        .split(' ')
                        .filter(Boolean)
                        .map((n) => n[0])
                        .slice(0, 2)
                        .join('')
                        .toUpperCase()}
                    </div>
                  )}
                </div>
                <div className="pd-member-info">
                  <div className="pd-member-name">{member.name}</div>
                  <div className="pd-member-details">
                    <span>
                      {member.characterClass?.name || 'Classe inconnue'}
                    </span>
                    <span className="pd-member-sep">•</span>
                    <span>{member.race?.name || 'Race inconnue'}</span>
                  </div>
                  <div className="pd-member-level">
                    Niveau {member.level || 1}
                  </div>
                </div>
              </Link>
            ))}
          </div>
        )}
      </section>
    </div>
  </>
  );
}

export default PartyDetail;

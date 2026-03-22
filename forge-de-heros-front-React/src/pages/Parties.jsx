import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { getParties } from '../utils/api';
import PartyCard from '../components/PartyCard';
import Header from '../components/Header';
import '../styles/Parties.scss';

function Parties() {
  const [parties, setParties] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [showAvailableOnly, setShowAvailableOnly] = useState(false);

  useEffect(() => {
    const fetchParties = async () => {
      try {
        setLoading(true);
        setError(null);
        const data = await getParties();
        // normaliser la réponse : accepter Array ou { data: [...] }
        const list = Array.isArray(data) ? data : data?.data ?? data?.hydra?.member ?? [];
        setParties(list);
      } catch (err) {
        setError(err.message ?? String(err));
      } finally {
        setLoading(false);
      }
    };

    fetchParties();
  }, []);

  // Filtre pour afficher uniquement les groupes avec places disponibles
  const partiesFiltered = parties.filter((party) => {
    if (!showAvailableOnly) return true;

    // Utiliser currentSize si présent, sinon fallback sur members.length
    const currentMembers = party.currentSize ?? party.members?.length ?? 0;
    const maxSize = party.maxSize ?? party.size ?? 0;
    return currentMembers < maxSize;
  });

  if (loading) {
    return (
      <>
        <Header />
        <div className="parties-page">
          <h1>Liste des Groupes</h1>
          <p className="parties-loading">Chargement des groupes...</p>
        </div>
      </>
    );
  }

  if (error) {
    return (
      <>
        <Header />
        <div className="parties-page">
          <h1>Liste des Groupes</h1>
          <p className="parties-error">Erreur : {error}</p>
        </div>
      </>
    );
  }

  return (
    <>
      <Header />
      <div className="parties-page">
        <h1>Liste des Groupes</h1>

        {/* Filtre pour groupes avec places disponibles */}
        <div className="parties-filter">
          <label>
            <input
              type="checkbox"
              checked={showAvailableOnly}
              onChange={(e) => setShowAvailableOnly(e.target.checked)}
            />
            <span>Afficher uniquement les groupes avec places disponibles</span>
          </label>
        </div>

        {/* Liste des groupes */}
        {partiesFiltered.length === 0 ? (
          <p className="parties-empty">
            {showAvailableOnly
              ? 'Aucun groupe avec places disponibles.'
              : 'Aucun groupe disponible.'}
          </p>
        ) : (
          <div className="parties-list">
            {partiesFiltered.map((party) => (
              <PartyCard key={party.id} partyID={party.id} data={party} />
            ))}
          </div>
        )}
      </div>
    </>
  );
}

export default Parties;

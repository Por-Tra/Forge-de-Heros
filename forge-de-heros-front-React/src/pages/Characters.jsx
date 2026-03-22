import { useState, useEffect } from 'react';
import { getCharacters } from '../utils/api';
import CharacterCard from '../components/CharacterCard';
import Header from '../components/Header';
import '../styles/Characters.scss';

function Characters() {
  const [characters, setCharacters] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [search, setSearch] = useState('');
  const [sort, setSort] = useState('character_name');

  useEffect(() => {
    const fetchCharacters = async () => {
      try {
        setLoading(true);
        setError(null);
        const data = await getCharacters();
        setCharacters(data);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchCharacters();
  }, []);

  // Filtre par nom, classe et race
  const q = search.trim().toLowerCase();
  const charactersFiltered = characters.filter((character) => {
    if (!q) return true;
    const name = String(character.name ?? '').toLowerCase();
    const className = String(
      character.characterClass?.name ?? '',
    ).toLowerCase();
    const raceName = String(character.race?.name ?? '').toLowerCase();
    return name.includes(q) || className.includes(q) || raceName.includes(q);
  });

  // Tri selon la méthode sélectionnée
  const charactersSorted = [...charactersFiltered].sort((a, b) => {
    if (sort === 'character_name') {
      return a.name.localeCompare(b.name, undefined, { sensitivity: 'base' });
    }
    if (sort === 'character_level') {
      return b.level - a.level; // niveau décroissant
    }
    return 0;
  });

  if (loading) {
    return (
      <>
        <Header />
        <div className="characters-page">
          <h1>Affichage des personnages</h1>
          <p className="characters-loading">Chargement des personnages...</p>
        </div>
      </>
    );
  }

  if (error) {
    return (
      <>
        <Header />
        <div className="characters-page">
          <h1>Affichage des personnages</h1>
          <p className="characters-error">Erreur : {error}</p>
        </div>
      </>
    );
  }

  return (
    <>
      <Header />
      <div className="characters-page">
        <h1>Affichage des personnages</h1>

        {/* Filtrage et tri */}
        <input
          type="text"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          placeholder="Rechercher par nom, classe ou race..."
        />

        <fieldset>
          <legend>Sélectionnez une méthode de tri&nbsp;:</legend>

          <div>
            <input
              type="radio"
              id="character_name"
              name="character_sort"
              value="character_name"
              checked={sort === 'character_name'}
              onChange={(e) => setSort(e.target.value)}
            />
            <label htmlFor="character_name">Nom du personnage (A → Z)</label>
          </div>

          <div>
            <input
              type="radio"
              id="character_level"
              name="character_sort"
              value="character_level"
              checked={sort === 'character_level'}
              onChange={(e) => setSort(e.target.value)}
            />
            <label htmlFor="character_level">
              Niveau (du plus élevé au plus faible)
            </label>
          </div>
        </fieldset>

        {/* Cartes filtrées et triées */}
        {charactersSorted.length === 0 ? (
          <p className="characters-empty">Aucun personnage trouvé.</p>
        ) : (
          <div>
            {charactersSorted.map((character) => (
              <CharacterCard
                key={character.id}
                characterID={character.id}
              />
            ))}
          </div>
        )}
      </div>
    </>
  );
}

export default Characters;

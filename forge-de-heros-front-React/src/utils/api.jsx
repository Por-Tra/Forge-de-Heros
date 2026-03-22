import {
  mockRaces,
  mockClasses,
  mockSkills,
  mockCharacters,
  mockParties,
} from './mockData';

// Toggle pour utiliser les données de test ou l'API réelle
const USE_MOCK_DATA = true;
const API_URL = '/api/v1';

//& Races
export async function getRaces() {
  if (USE_MOCK_DATA) {
    return Promise.resolve(mockRaces);
  }
  const response = await fetch(`${API_URL}/races`);
  if (!response.ok) throw new Error('Erreur lors du chargement des races');
  return response.json();
}

export async function getRace(id) {
  if (USE_MOCK_DATA) {
    const race = mockRaces.find((r) => r.id === Number(id));
    if (!race) throw new Error('Race introuvable');
    return Promise.resolve(race);
  }
  const response = await fetch(`${API_URL}/races/${id}`);
  if (!response.ok) throw new Error('Race introuvable');
  return response.json();
}

//& Classes
export async function getClasses() {
  if (USE_MOCK_DATA) {
    return Promise.resolve(mockClasses);
  }
  const response = await fetch(`${API_URL}/classes`);
  if (!response.ok) throw new Error('Erreur lors du chargement des classes');
  return response.json();
}

export async function getClass(id) {
  if (USE_MOCK_DATA) {
    const characterClass = mockClasses.find((c) => c.id === Number(id));
    if (!characterClass) throw new Error('Classe introuvable');
    return Promise.resolve(characterClass);
  }
  const response = await fetch(`${API_URL}/classes/${id}`);
  if (!response.ok) throw new Error('Classe introuvable');
  return response.json();
}

//& Compétences
export async function getSkills() {
  if (USE_MOCK_DATA) {
    return Promise.resolve(mockSkills);
  }
  const response = await fetch(`${API_URL}/skills`);
  if (!response.ok)
    throw new Error('Erreur lors du chargement des compétences');
  return response.json();
}

//& Personnages
export async function getCharacters() {
  if (USE_MOCK_DATA) {
    return Promise.resolve(mockCharacters);
  }
  const response = await fetch(`${API_URL}/characters`);
  if (!response.ok)
    throw new Error('Erreur lors du chargement des personnages');
  return response.json();
}

export async function getCharacter(id) {
  if (USE_MOCK_DATA) {
    const character = mockCharacters.find((c) => c.id === Number(id));
    if (!character) throw new Error('Personnage introuvable');
    return Promise.resolve(character);
  }
  const response = await fetch(`${API_URL}/characters/${id}`);
  if (!response.ok) throw new Error('Personnage introuvable');
  return response.json();
}

//& Groupes
export async function getParties() {
  if (USE_MOCK_DATA) {
    return Promise.resolve(mockParties);
  }
  const response = await fetch(`${API_URL}/parties`);
  if (!response.ok) throw new Error('Erreur lors du chargement des groupes');
  return response.json();
}

export async function getParty(id) {
  if (USE_MOCK_DATA) {
    const party = mockParties.find((p) => p.id === Number(id));
    if (!party) throw new Error('Groupe introuvable');
    return Promise.resolve(party);
  }
  const response = await fetch(`${API_URL}/parties/${id}`);
  if (!response.ok) throw new Error('Groupe introuvable');
  return response.json();
}

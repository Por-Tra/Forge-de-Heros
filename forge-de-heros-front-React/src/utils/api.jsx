
const API_URL = 'https://127.0.0.1:8000/api/v1';

//& Races
export async function getRaces() {
  const response = await fetch(`${API_URL}/races`);
  if (!response.ok) throw new Error('Erreur lors du chargement des races');
  return response.json();
}

export async function getRace(id) {
  const response = await fetch(`${API_URL}/races/${id}`);
  if (!response.ok) throw new Error('Race introuvable');
  return response.json();
}

//& Classes
export async function getClasses() {
  const response = await fetch(`${API_URL}/classes`);
  if (!response.ok) throw new Error('Erreur lors du chargement des classes');
  return response.json();
}

export async function getClass(id) {
  const response = await fetch(`${API_URL}/classes/${id}`);
  if (!response.ok) throw new Error('Classe introuvable');
  return response.json();
}

//& Compétences
export async function getSkills() {
  const response = await fetch(`${API_URL}/skills`);
  if (!response.ok)
    throw new Error('Erreur lors du chargement des compétences');
  return response.json();
}

//& Personnages
export async function getCharacters() {
  const response = await fetch(`${API_URL}/characters`);
  if (!response.ok)
    throw new Error('Erreur lors du chargement des personnages');
  return response.json();
}

export async function getCharacter(id) {
  const response = await fetch(`${API_URL}/characters/${id}`);
  if (!response.ok) throw new Error('Personnage introuvable');
  return response.json();
}

//& Groupes
export async function getParties() {
  const response = await fetch(`${API_URL}/parties`);
  if (!response.ok) throw new Error('Erreur lors du chargement des groupes');
  return response.json();
}

export async function getParty(id) {
  const response = await fetch(`${API_URL}/parties/${id}`);
  if (!response.ok) throw new Error('Groupe introuvable');
  return response.json();
}

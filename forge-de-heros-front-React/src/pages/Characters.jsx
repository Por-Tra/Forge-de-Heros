import { useState } from "react"
import CharacterCard from "../components/CharacterCard"
import jesusAvatar from "../assets/avatars/jesus.jpg"
import "../styles/Characters.scss" // <-- import scss spécifique

function Characters()
{
    const [search, setSearch] = useState("");
    const [sort, setSort] = useState("character_name");

    const characters = [
        {
            id: 1,
            avatar: jesusAvatar,
            name: "Jesus",
            class: "Dieu",
            race: "Humain",
            level: 67
        }, 
        {
            id: 2,
            avatar: null,
            name: "Simple Humain",
            class: "nullos",
            race: "Humain",
            level: 1
        }, 
        {
            id: 3,
            avatar: null,
            name: "gros caca",
            class: "caca",
            race: "Dieu",
            level: 5959
        }, 
        {
            id: 4,
            avatar: null,
            name: "AAAAAAAAAAA",
            class: "caca",
            race: "Dieu",
            level: 6
        },
    ]

    // Filtre par nom, classe et race (gestion des valeurs nulles)
    const q = search.trim().toLowerCase();
    const charactersFiltered = characters.filter((character) => {
        if (!q) return true;
        return ["name", "class", "race"].some((key) =>
            String(character[key] ?? "").toLowerCase().includes(q)
        );
    });

    // Tri selon la méthode sélectionnée
    const charactersSorted = [...charactersFiltered].sort((a, b) => {
        if (sort === "character_name") {
            return a.name.localeCompare(b.name, undefined, { sensitivity: "base" });
        }
        if (sort === "character_level") {
            return b.level - a.level; // niveau décroissant
        }
        return 0;
    });

    return (
        <div className="characters-page">
            <h1>Affichage des personnages</h1>

            {/* Filtrage et tri */}
            <input
                type="text"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                placeholder="Rechercher..."
            />

            <fieldset>
                <legend>Sélectionnez une méthode de tri&nbsp;:</legend>

                <div>
                    <input
                        type="radio"
                        id="character_name"
                        name="character_sort"
                        value="character_name"
                        checked={sort === "character_name"}
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
                        checked={sort === "character_level"}
                        onChange={(e) => setSort(e.target.value)}
                    />
                    <label htmlFor="character_level">Niveau (du plus élevé au plus faible)</label>
                </div>
            </fieldset>

            {/* Cartes filtrées et triées */}
            <div>
                {charactersSorted.map((character) => (
                    <CharacterCard
                        key={`${character.id}-${character.name}`}
                        characterID={character.id}
                        data={character}
                    />
                ))}
            </div>
        </div>
    );
}

export default Characters;
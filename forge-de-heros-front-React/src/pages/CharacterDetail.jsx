import { useParams } from "react-router-dom";
import JesusAvatar from "../assets/avatars/jesus.jpg";
import "../styles/CharacterDetail.scss";

//import { getCharacter } from "../utils/api";

function CharacterDetail() {
    const { id } = useParams();
    //const data = getCharacter(id);

    //! Données de test
    const characters = [
        {
            id: 1,
            avatar: JesusAvatar,
            name: "Jesus",
            level: 67,
            healthPoints: 10,
            class: {
                name: "Dieu",
                description: "Etre tout puissant",
                healthDice: 10,
                skills: [
                    { name: "Extermination", ability: ["STR", "WIS"] }
                ]
            },
            race: {
                name: "Humain",
                description: "Un simple Humain"
            },
            stats: {
                Force: 10,
                "Dextérité": 5,
                Constitution: 10,
                Intelligence: 20,
                Sagesse: 20,
                Charisme: 20
            },
            parties: {
                party1: { name: "PSG" },
                party2: { name: "France" }
            }
        },
        {
            id: 2,
            avatar: null,
            name: "Simple Humain",
            level: 1,
            healthPoints: 6,
            class: {
                name: "Nullos",
                description: "Un aventurier débutant, pas très costaud.",
                healthDice: 6,
                skills: [
                    { name: "Coup maladroit", ability: ["STR"] }
                ]
            },
            race: {
                name: "Humain",
                description: "Un citoyen ordinaire"
            },
            stats: {
                Force: 6,
                "Dextérité": 8,
                Constitution: 6,
                Intelligence: 7,
                Sagesse: 5,
                Charisme: 6
            },
            parties: {}
        },
        {
            id: 3,
            avatar: null,
            name: "gros caca",
            level: 5959,
            healthPoints: 999,
            class: {
                name: "Caca",
                description: "Brute inclassable, puissance démesurée.",
                healthDice: 12,
                skills: [
                    { name: "Ravage cosmique", ability: ["STR", "CON"] }
                ]
            },
            race: {
                name: "Dieu",
                description: "Un être hors norme"
            },
            stats: {
                Force: 30,
                "Dextérité": 10,
                Constitution: 30,
                Intelligence: 5,
                Sagesse: 5,
                Charisme: 8
            },
            parties: {}
        },
        {
            id: 4,
            avatar: null,
            name: "AAAAAAAAAAA",
            level: 6,
            healthPoints: 18,
            class: {
                name: "Caca",
                description: "Type excentrique et bruyant.",
                healthDice: 8,
                skills: [
                    { name: "Cri perçant", ability: ["CHA"] }
                ]
            },
            race: {
                name: "Dieu",
                description: "Origine mystérieuse"
            },
            stats: {
                Force: 9,
                "Dextérité": 7,
                Constitution: 10,
                Intelligence: 6,
                Sagesse: 5,
                Charisme: 4
            },
            parties: {}
        }
    ];

    const characterChoosed = characters.find(
        (character) => character.id === Number(id),
    );

    if (characterChoosed == null) {
        return <p style={{ color: "#f3e6b8" }}>Personnage inexistant.</p>;
    }

    // Stats scale: min 8, max 15
    const STAT_MIN = 8;
    const STAT_MAX = 15;
    const statEntries = Object.entries(characterChoosed.stats || {});

    const clamp = (v, a, b) => Math.max(a, Math.min(b, v));
    const valueToPercent = (v) => {
        const clamped = clamp(v, STAT_MIN, STAT_MAX);
        return Math.round(((clamped - STAT_MIN) / (STAT_MAX - STAT_MIN)) * 100);
    };

    // map ability codes to stat labels
    const ABILITY_MAP = {
        STR: "Force",
        DEX: "Dextérité",
        CON: "Constitution",
        INT: "Intelligence",
        WIS: "Sagesse",
        CHA: "Charisme"
    };

    // initials fallback for avatar
    const getInitials = (name = "") =>
        name
            .split(" ")
            .filter(Boolean)
            .map((n) => n[0])
            .slice(0, 2)
            .join("")
            .toUpperCase();

    const skills = (characterChoosed.class && characterChoosed.class.skills) || [];
    const parties = characterChoosed.parties ? Object.values(characterChoosed.parties).map(p => p.name).filter(Boolean) : [];

    return (
        <div className="character-detail-page">
            <h1 className="cd-title">{characterChoosed.name}</h1>

            <div className="cd-top">
                {characterChoosed.avatar ? (
                    <img
                        className="cd-avatar"
                        src={characterChoosed.avatar}
                        alt={`Avatar de ${characterChoosed.name}`}
                    />
                ) : (
                    <div className="cd-avatar cd-avatar--placeholder" aria-hidden="true">
                        {getInitials(characterChoosed.name)}
                    </div>
                )}

                <a href="/characters" className="cd-back-link">← Liste des personnages</a>
                
                <div className="cd-meta">
                    <p className="cd-meta-line">
                        <span className="cd-meta-label">Classe:</span>
                        <strong>{characterChoosed.class?.name}</strong>
                    </p>
                    {characterChoosed.class?.description && (
                        <p className="cd-meta-desc">{characterChoosed.class.description}</p>
                    )}

                    <p className="cd-meta-line">
                        <span className="cd-meta-label">Race:</span>
                        <strong>{characterChoosed.race?.name}</strong>
                    </p>
                    {characterChoosed.race?.description && (
                        <p className="cd-meta-desc">{characterChoosed.race.description}</p>
                    )}

                    <p className="cd-meta-line">
                        <span className="cd-meta-label">Niveau:</span>
                        <strong>{characterChoosed.level}</strong>
                        <span className="cd-meta-sep"> — </span>
                        <span className="cd-meta-label">Vie:</span>
                        <strong>{characterChoosed.healthPoints}</strong>
                    </p>
                </div>
            </div>

            <section className="cd-stats">
                <h2>Stats</h2>
                <div className="cd-stats-list">
                    {statEntries.map(([label, rawVal]) => {
                        const originalVal = typeof rawVal === "number" ? rawVal : Number(rawVal) || 0;
                        const clampedVal = clamp(originalVal, STAT_MIN, STAT_MAX);
                        const pct = valueToPercent(originalVal);
                        const titleExtra = originalVal !== clampedVal ? `Valeur originale: ${originalVal}` : null;

                        return (
                            <div className="cd-stat-row" key={label} title={titleExtra || undefined}>
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
                                    <div className="cd-stat-fill" style={{ width: `${pct}%` }} />
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
                    Échelle : {STAT_MIN} — {STAT_MAX}. Les valeurs affichées sont ramenées dans cet intervalle
                    (la valeur originale est visible au survol si différente).
                </p>
            </section>

            <section className="cd-skills" aria-labelledby="skills-title">
                <h2 id="skills-title">Compétences</h2>
                {skills.length === 0 ? (
                    <p className="cd-muted">Aucune compétence définie.</p>
                ) : (
                    <div className="cd-skills-list">
                        {skills.map((s, idx) => (
                            <div className="cd-skill-row" key={idx}>
                                <div className="cd-skill-name">{s.name}</div>
                                <div className="cd-skill-abilities">
                                    {(s.ability || []).map((ab, i) => {
                                        const statLabel = ABILITY_MAP[ab] || ab;
                                        return (
                                            <span className="cd-ability-badge" key={i} title={statLabel}>
                                                {statLabel}
                                            </span>
                                        );
                                    })}
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
                    <div className="cd-parties-list"> {/*TODO Mettre les liens vers les groupes*/}
                        {parties.map((p, i) => (
                            <span className="cd-party-badge" key={i}>{p}</span>
                        ))}
                    </div>
                )}
            </section>
        </div>
    );
}

export default CharacterDetail;

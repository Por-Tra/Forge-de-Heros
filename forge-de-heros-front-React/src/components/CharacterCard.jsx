//import { getCharacter } from "../utils/api";
import { Link } from "react-router-dom"; // ajouté
import jesusAvatar from "../assets/avatars/jesus.jpg"
import "../styles/Character.Card.scss";

function CharacterCard({ characterID })
{
    // const data = getCharacter(characterID);

    /* 
      Informations a afficher:
        - Avatar
        - Nom
        - Classe
        - Race
        - Niveau 
     */

    const data = {
        id: 1,
        avatar: jesusAvatar,
        name: "Jesus",
        class: "Dieu",
        race: "Humain",
        level: 67
    }
   

    return (
        <div className="character-card">
            <div className="character-card__header">
                <img className="character-card__avatar" src={data.avatar} alt={`Avatar de ${data.name} (#${characterID})`} />
                <div>
                    <div className="character-card__name">{data.name}</div>
                    <div className="character-card__rune" aria-hidden="true"></div>
                </div>
            </div>

            <div className="character-card__body">
                <div className="character-card__stats">
                    <div className="character-card__stat"><span>Classe</span><span className="value">{data.class}</span></div>
                    <div className="character-card__stat"><span>Race</span><span className="value">{data.race}</span></div>
                    <div className="character-card__stat"><span>Niveau</span><span className="value">{data.level}</span></div>
                </div>

                <Link className="character-card__link" to={`/characters/${characterID}`}>
                    Plus d'informations
                </Link>
            </div>
        </div>
    );
}

export default CharacterCard;
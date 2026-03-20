import { Link } from "react-router-dom";
import { getParties } from "../utils/api";


import Header from "../components/Header";



function PartieLine({partieID, name, memberNumber, remainingSpot}){

    return(
        <Link className="partie" to={`/parties/${partieID}`}>
            <div className="partieInfo">{name}</div>
            <div className="partieInfo">{memberNumber}</div>
            <div className="partieInfo">{remainingSpot}</div>
        </Link>
        
    )
}



function Parties(){

    //const parties = getParties()

    const parties = [
        {id: 1, name:"Bob Clan", memberNumber:5, remainingSpot:5}, 
        {id: 2, name:"Baka Klan", memberNumber:500, remainingSpot:2}
    ]

    return(
        <div>
            <Header/>
            <h2>Liste des Groupes</h2>
            
            <div className="partiesList">
                
                {parties.map((partie) => (
                    <PartieLine
                        partieID={partie.id}
                        name={partie.name}
                        memberNumber={partie.memberNumber}
                        remainingSpot = {partie.remainingSpot}
                    />
                ))}

            </div>

            
            

        </div>
    )
}

export default Parties
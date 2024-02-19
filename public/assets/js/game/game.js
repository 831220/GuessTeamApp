
const processChoice = (playerElement, selectElement, pointsElement)=>{
    let score = pointsElement.innerText;
    let selectedTeam = selectElement.value;
    let correctTeam = playerElement.textContent;
    if (selectedTeam ===correctTeam){

        score ++;
        pointsElement.innerText = score;
    }else{
        alert('incorrecto');
    }

}


const fillTeamSelector = (playersArray, teamSelectElement) => {debugger

    let uniqueTeams = new Set();


    for (let key in playersArray) {
        if (playersArray.hasOwnProperty(key)) {
            let team = playersArray[key];
            uniqueTeams.add(team);
        }
    }

    teamSelectElement.innerHTML = '';


    uniqueTeams.forEach(team => {
        let option = document.createElement('option');
        option.text = team;
        option.value = team;
        teamSelectElement.add(option);
    });
};

const fillPlayerInput = (firstPlayerName, playerTeam, playerInputElement, restPlayersElement)=>{
        playerInputElement.innerText = playerTeam;
        playerInputElement.setAttribute('value',firstPlayerName);

        restPlayersElement.innerText = Number(restPlayersElement.innerText) - 1;

}



let players = [];
let editingId = null;

const tableBody = document.getElementById("player-table");
const formView = document.getElementById("form-view");
const listView = document.getElementById("list-view");
const statsView = document.getElementById("stats-view");

const navList = document.getElementById("nav-list");
const navAdd = document.getElementById("nav-add");
const navStats = document.getElementById("nav-stats");

const form = document.getElementById("player-form");
const formTitle = document.getElementById("form-title");
const formError = document.getElementById("form-error");

function seedData() {
  return [
    { id: 1, name: "LeBron James", team: "Lakers", position: "SF", ppg: 27.1, years: 23 },
    { id: 2, name: "Stephen Curry", team: "Warriors", position: "PG", ppg: 24.5, years: 17 },
    { id: 3, name: "Kevin Durant", team: "Rockets", position: "SF", ppg: 27.3, years: 18 },
    { id: 4, name: "Giannis Antetokounmpo", team: "Bucks", position: "PF", ppg: 29.8, years: 13 },
    { id: 5, name: "Nikola Jokic", team: "Nuggets", position: "C", ppg: 26.4, years: 11 },
    { id: 6, name: "Luka Doncic", team: "Lakers", position: "PG", ppg: 28.7, years: 8 },
    { id: 7, name: "Joel Embiid", team: "76ers", position: "C", ppg: 30.1, years: 11 },
    { id: 8, name: "Jayson Tatum", team: "Celtics", position: "SF", ppg: 26.9, years: 9 },
    { id: 9, name: "Damian Lillard", team: "Trailblazers", position: "PG", ppg: 25.1, years: 14 },
    { id: 10, name: "Jimmy Butler", team: "Warriors", position: "SF", ppg: 22.3, years: 15 },
    { id: 11, name: "Kawhi Leonard", team: "Clippers", position: "SF", ppg: 24.8, years: 14 },
    { id: 12, name: "Devin Booker", team: "Suns", position: "SG", ppg: 27.1, years: 10 },
    { id: 13, name: "Anthony Davis", team: "Mavericks", position: "PF", ppg: 24.2, years: 13 },
    { id: 14, name: "Ja Morant", team: "Grizzlies", position: "PG", ppg: 26.1, years: 7 },
    { id: 15, name: "Zion Williamson", team: "Pelicans", position: "PF", ppg: 25.0, years: 6 },
    { id: 16, name: "Trae Young", team: "Wizards", position: "PG", ppg: 25.5, years: 8 },
    { id: 17, name: "Paul George", team: "Clippers", position: "SG", ppg: 23.8, years: 15 },
    { id: 18, name: "Bradley Beal", team: "Clippers", position: "SG", ppg: 22.5, years: 13 },
    { id: 19, name: "Donovan Mitchell", team: "Cavaliers", position: "SG", ppg: 27.6, years: 9 },
    { id: 20, name: "Bam Adebayo", team: "Heat", position: "C", ppg: 20.4, years: 9 },
    { id: 21, name: "Jamal Murray", team: "Nuggets", position: "PG", ppg: 20.0, years: 9 },
    { id: 22, name: "Shai Gilgeous-Alexander", team: "Thunder", position: "SG", ppg: 30.1, years: 8 },
    { id: 23, name: "De'Aaron Fox", team: "Spurs", position: "PG", ppg: 25.2, years: 9 },
    { id: 24, name: "Jaren Jackson Jr.", team: "Grizzlies", position: "PF", ppg: 22.4, years: 8 },
    { id: 25, name: "Jrue Holiday", team: "Trailblazers", position: "PG", ppg: 18.5, years: 15 },
    { id: 26, name: "Karl-Anthony Towns", team: "Knicks", position: "C", ppg: 23.1, years: 11 },
    { id: 27, name: "Anthony Edwards", team: "Timberwolves", position: "SG", ppg: 26.0, years: 6 },
    { id: 28, name: "Pascal Siakam", team: "Pacers", position: "PF", ppg: 22.0, years: 10 },
    { id: 29, name: "Domantas Sabonis", team: "Kings", position: "C", ppg: 19.4, years: 11 },
    { id: 30, name: "Tyrese Haliburton", team: "Pacers", position: "PG", ppg: 20.1, years: 6 }
  ];
}

function loadPlayers() {
  const stored = localStorage.getItem("nbaPlayers");
  if (stored) {
    players = JSON.parse(stored);
  } else {
    players = seedData();
    localStorage.setItem("nbaPlayers", JSON.stringify(players));
  }
}

function savePlayers() {
  localStorage.setItem("nbaPlayers", JSON.stringify(players));
}

function renderTable() {
  tableBody.innerHTML = "";
  players.forEach(player => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${player.name}</td>
      <td>${player.team}</td>
      <td>${player.position}</td>
      <td>${player.ppg}</td>
      <td>${player.years}</td>
      <td>
        <button id = "editBtn" onclick="editPlayer(${player.id})">Edit</button>
        <button id="deleteBtn" onclick="deletePlayer(${player.id})">Delete</button>
      </td>
    `;
    tableBody.appendChild(row);
  });
}

// Shows one view at a time
function showView(view) {
  listView.classList.add("hidden");
  formView.classList.add("hidden");
  statsView.classList.add("hidden");
  view.classList.remove("hidden");
}

navList.onclick = () => showView(listView);

navAdd.onclick = () => {
  form.reset();
  editingId = null;
  formTitle.textContent = "Add Player";
  formError.textContent = "";
  showView(formView);
};

// Open stats view and recalculates stats
navStats.onclick = () => {
  renderStats();
  showView(statsView);
};

form.onsubmit = function (e) {
  e.preventDefault();

  const name = document.getElementById("name").value.trim();
  const team = document.getElementById("team").value.trim();
  const position = document.getElementById("position").value;
  const ppg = parseFloat(document.getElementById("ppg").value);
  const years = parseInt(document.getElementById("years").value);

  if (!name || !team || isNaN(ppg) || isNaN(years)) {
    formError.textContent = "All fields are required.";
    return;
  }

  if (ppg < 0 || ppg > 50 || years < 0 || years > 25) {
    formError.textContent = "PPG must be 0–50 and Years must be 0–25.";
    return;
  }

  if (editingId) {
    const player = players.find(p => p.id === editingId);
    player.name = name;
    player.team = team;
    player.position = position;
    player.ppg = ppg;
    player.years = years;
  } else {
    const newPlayer = {
      id: Date.now(),
      name,
      team,
      position,
      ppg,
      years
    };
    players.push(newPlayer);
  }

  savePlayers();
  renderTable();
  showView(listView);
};

// Loads selected player data into the form for editing
function editPlayer(id) {
  const player = players.find(p => p.id === id);
  document.getElementById("name").value = player.name;
  document.getElementById("team").value = player.team;
  document.getElementById("position").value = player.position;
  document.getElementById("ppg").value = player.ppg;
  document.getElementById("years").value = player.years;
  editingId = id;
  formTitle.textContent = "Edit Player";
  formError.textContent = "";
  showView(formView);
}


// Delete function
function deletePlayer(id) {
  if (confirm("Are you sure you want to delete this player?")) {
    players = players.filter(p => p.id !== id);
    savePlayers();
    renderTable();
  }
}

// Stats render
function renderStats() {
  document.getElementById("total-players").textContent =
    "Total Players: " + players.length;

  const avg = (players.reduce((sum, p) => sum + p.ppg, 0) / players.length).toFixed(2);
  document.getElementById("avg-ppg").textContent =
    "Average PPG: " + avg;

  const counts = { PG: 0, SG: 0, SF: 0, PF: 0, C: 0 };
  players.forEach(p => counts[p.position]++);

  document.getElementById("position-counts").innerHTML =
    "Players by Position:<br>" +
    `PG: ${counts.PG}<br>` +
    `SG: ${counts.SG}<br>` +
    `SF: ${counts.SF}<br>` +
    `PF: ${counts.PF}<br>` +
    `C: ${counts.C}`;

  const avgYears = (players.reduce((sum, p) => sum + p.years, 0) / players.length).toFixed(2);
  document.getElementById("avg-years").textContent = "Average Years: " + avgYears;
}

// Initial app load
loadPlayers();
renderTable();

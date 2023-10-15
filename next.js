const next = document.querySelector('#next');
const tbody = document.querySelector('#matches');
let from = 1;

next.addEventListener('click', async e => {
    e.preventDefault();
    from++;
    const response = await fetch(`nextmatches.php?from=${encodeURIComponent(from)}`);
    const result = await response.json();
    tbody.innerHTML = result.map(match => `
        <tr>
            <td>${match.team1}</td>
            <td>${match.home.score} : ${match.away.score}</td>
            <td>${match.team2}</td>
            <td>${match.date}</td>
        </tr>    
    `).join("");
});
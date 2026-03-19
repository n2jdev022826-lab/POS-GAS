/* ================= DATE & TIME ================= */

function updateDateTime() {
  const now = new Date();

  const days = [
    "Sunday",
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
  ];
  const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];

  const dayName = days[now.getDay()];
  const monthName = months[now.getMonth()];
  const day = now.getDate();
  const year = now.getFullYear();

  let hours = now.getHours();
  let minutes = now.getMinutes().toString().padStart(2, "0");
  let seconds = now.getSeconds().toString().padStart(2, "0");

  const ampm = hours >= 12 ? "P.M." : "A.M.";

  hours = hours % 12;
  hours = hours ? hours : 12;

  const formatted = `${dayName}, ${monthName} ${day}, ${year} | ${hours}:${minutes}:${seconds} ${ampm}`;

  document.getElementById("datetime").innerHTML = formatted;
}

setInterval(updateDateTime, 1000);
updateDateTime();

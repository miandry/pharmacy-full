export function formatDate(dateStr, createdTimestamp = null) {
  let date;

  // 1. Si la date standard existe → on l'utilise
  if (dateStr) {
    date = new Date(dateStr);
  }
  // 2. Sinon si un timestamp (ex: 1723699598) existe → on le convertit
  else if (createdTimestamp) {
    date = new Date(Number(createdTimestamp) * 1000);
  }
  // 3. Sinon rien à afficher
  else {
    return "";
  }

  // Formattage FR longue date
  const formatted = date.toLocaleDateString("fr-FR", {
    day: "2-digit",
    month: "long",
    year: "numeric",
  });

  // Majuscule sur le mois
  return formatted.replace(
    /(\d{2} )([a-z]+)/,
    (match, day, month) => day + month.charAt(0).toUpperCase() + month.slice(1)
  );
}

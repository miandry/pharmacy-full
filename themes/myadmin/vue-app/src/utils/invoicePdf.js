// src/utils/invoicePdf.js
import { formatDate } from "./formateDate.js";
// /sites/default/files/2025-11/logo-vonjyaina.png
function formatPrice(value) {
  return `${Number(value)
    .toLocaleString("fr-FR")
    .replace(/\u202F/g, " ")} Ar`;
}

// -------------------------
// Utilitaire pour convertir logo en base64
// -------------------------
export function getBase64FromUrl(url) {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.crossOrigin = "Anonymous"; // pour éviter CORS
    img.onload = () => {
      const canvas = document.createElement("canvas");
      canvas.width = img.width;
      canvas.height = img.height;
      const ctx = canvas.getContext("2d");
      ctx.drawImage(img, 0, 0);
      const dataURL = canvas.toDataURL("image/png");
      resolve(dataURL);
    };
    img.onerror = (err) => reject(err);
    img.src = url;
  });
}

export async function generateInvoicePdf(order, statusMap, pdfMake) {
  const client = order.field_client;
  const articles = order.field_articles;

  const articleRows = articles.map((a) => [
    { text: a.field_article.title },
    { text: a.field_quantite.toString(), alignment: "right" },
    { text: formatPrice(a.field_prix_unitaire), alignment: "right" },
    {
      text: formatPrice(a.field_prix_unitaire * a.field_quantite),
      alignment: "right",
    },
  ]);

  const total = order.field_total_vente || 0;
  const statusText = statusMap[order.field_status]?.text || "—";

  // Convertir logo en base64
  const logoBase64 = await getBase64FromUrl(
    "/sites/default/files/2025-11/logo-vonjyaina.png"
  );

  const docDefinition = {
    content: [
      // Header avec logo à droite
      {
        columns: [
          {
            image: logoBase64,
            width: 140,
            alignment: "left",
          },
          [
            {
              text: `Facture #${order.title}`,
              style: "header",
              alignment: "right",
              margin: [0, 0, 0, 2],
            },
            {
              text: `Statut : ${statusText}`,
              style: "subheader",
              alignment: "right",
              margin: [0, 0, 0, 2],
            },
            {
              text: `Date : ${formatDate(order.field_date, order.created)}`,
              alignment: "right",
            },
          ],
        ],
        columnGap: 10,
        margin: [0, 0, 0, 20],
      },

      { text: "Informations client", style: "subheader" },
      {
        columns: [
          [
            { text: client.title, margin: [0, 0, 0, 2], style: "clientName" },
            { text: `Téléphone : ${client.field_phone || ""}`, margin: [0, 0, 0, 2] },
            client.field_assurance == 1
              ? { text: "Client avec assurance", margin: [0, 0, 0, 2], color: "#4f46e5" }
              : "",
          ],
        ],
        margin: [0, 0, 0, 20],
      },

      { text: "Produits commandés", style: "subheader" },
      {
        table: {
          headerRows: 1,
          widths: ["*", "10%", "20%", "20%"],
          body: [
            [
              { text: "Article", bold: true },
              { text: "Qté", bold: true, alignment: "right" },
              { text: "Prix unitaire (Ar)", bold: true, alignment: "right" },
              { text: "Total (Ar)", bold: true, alignment: "right" },
            ],
            ...articleRows,
          ],
        },
        margin: [0, 0, 0, 20],
        layout: "lightHorizontalLines",
      },

      {
        table: {
          widths: ["60%", "40%"],
          body: [
            [
              { text: "TOTAL", bold: true, fontSize: 14 },
              {
                text: formatPrice(total),
                bold: true,
                alignment: "right",
                color: "#2563eb",
                fontSize: 14,
              },
            ],
          ],
        },
      },
    ],

    styles: {
      header: { fontSize: 20, bold: true, margin: [0, 0, 0, 10] },
      subheader: { fontSize: 14, bold: true, margin: [0, 10, 0, 5] },
      clientName: { fontSize: 12, bold: true },
    },

    defaultStyle: {
      fontSize: 10,
    },
  };

  pdfMake.createPdf(docDefinition).download(`facture_${order.title}.pdf`);
}

import { getDetails, getLists, saveItem } from "./api";

export function getOrders(params) {
  return getLists("node", "commande", params);
}

export function getOrder(id, params) {
  return getDetails("node", "commande", id, params);
}


export function saveOrder(params) {
  return saveItem(params);
}

export function saveParagraph(params) {
  return saveItem(params);
}
import { getLists, saveItem } from "./api";

export function getOrders(params) {
  return getLists("node", "client", params);
}

export function saveOrder(params) {
  return saveItem(params);
}

export function saveParagraph(params) {
  return saveItem(params);
}
import { getLists } from './api'

// Exemple 1 : récupérer tous les articles
export function getArticles(parameters = null) {
  // Tu peux réutiliser la fonction générique `getLists`
  return getLists('node', 'article', parameters)
}
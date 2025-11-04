import { getLists } from './api'

export function getArticles(parameters = null) {
  return getLists('node', 'article', parameters)
}
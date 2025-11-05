import { getLists } from './api'

export function getArticles(params) {
  return getLists('node', 'article', params)
}
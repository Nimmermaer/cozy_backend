// src/views/ViewRenderers.ts (vorher StaticViews.ts)

import {NewsItem} from '../models/types';
// Die NewsService Instanz wird von main.ts übergeben oder hier erzeugt.
// Wir erstellen sie hier, um die Views testbar zu halten.
import {NewsService} from '../services/NewsService';

const newsService = new NewsService();

// Hilfsfunktion zum Erstellen einer News-Karte
function createNewsCard(news: NewsItem, isSaved: boolean): string {
    const date = new Date(news.date).toLocaleDateString();

    return `
        <article class="news-card" data-news-id="${news.id}">
            <h3><a href="#/news/${news.id}">${news.title}</a></h3>
            <p>${news.teaser}</p>
            <div class="news-card-footer">
                <small>Veröffentlicht: ${date}</small>
                <button 
                    class="contrast ${isSaved ? 'secondary' : 'primary'}"
                    data-action="toggle-save" 
                    data-id="${news.id}"
                >
                    ${isSaved ? '🗑️ Entfernen' : '💾 Merken'}
                </button>
            </div>
        </article>
    `;
}

// --- RENDERING-FUNKTIONEN ---

export async function renderNewsListView(): Promise<string> {
    const newsList = await newsService.fetchAllNews();
    const isOnline = navigator.onLine;

    if (newsList.length === 0) {
        return `
            <h2>Aktuelle News</h2>
            <p>Zur Zeit sind keine News verfügbar.</p>
        `;
    }

    const htmlCards = newsList.map(news => {
        const isSaved = newsService.isNewsSaved(news.id);
        return createNewsCard(news, isSaved);
    }).join('');

    return `
        <h2>Aktuelle News (${isOnline ? 'Online' : 'Offline/Fallback'})</h2>
        <div class="grid">
            ${htmlCards}
        </div>
        <script>
        </script>
    `;
}

export async function renderImprintView(): Promise<string> {
    // ... (unverändert)
    return `<h2>Impressum</h2><p>...</p>`;
}


export async function renderSavedNewsView(): Promise<string> {
    const savedNews = newsService.getSavedNews();
    const isOffline = !navigator.onLine;

    const offlineInfo = isOffline ? `
        <article id="saved-news-offline-info">
            <p><strong>Offline-Modus aktiv:</strong> Diese ${savedNews.length} News-Artikel wurden lokal gespeichert und sind offline verfügbar.</p>
        </article>
    ` : '';

    if (savedNews.length === 0) {
        return `
            <h2>Gemerkte News</h2>
            ${offlineInfo}
            <p>Sie haben noch keine News gespeichert. Speichern Sie Artikel, um sie offline verfügbar zu machen!</p>
        `;
    }

    const htmlCards = savedNews.map(news => {
        return createNewsCard(news, true);
    }).join('');

    return `
        <h2>Gemerkte News (${savedNews.length} Artikel)</h2>
        ${offlineInfo}
        <div class="grid">
            ${htmlCards}
        </div>
        <p><small>Hinweis: Die Detailseiten dieser Artikel sind auch offline verfügbar, solange sie gemerkt sind.</small></p>
        <script>
            // Platzhalter für Event-Listener
        </script>
    `;
}

export async function renderNewsDetailView(id: string): Promise<string> {
    const newsId = Number(id);
    if (isNaN(newsId)) {
        return `<h2>Fehler</h2><p>Ungültige News-ID.</p>`;
    }


    const newsItem = await newsService.fetchNewsById(newsId);

    if (!newsItem) {
        return `<h2>News nicht gefunden</h2><p>Der Artikel mit ID ${id} existiert nicht oder ist offline nicht verfügbar.</p>`;
    }

    const isSaved = newsService.isNewsSaved(newsId);

    return `
        <p><a href="#/" class="secondary" role="button">← Zurück zur Übersicht</a></p>
        <article class="news-detail">
            <h1>${newsItem.title}</h1>
            <p><small>Veröffentlicht am: ${new Date(newsItem.date).toLocaleDateString()}</small></p>
            
            <hr>

            <button 
                class="contrast ${isSaved ? 'secondary' : 'primary'}"
                data-action="toggle-save" 
                data-id="${newsItem.id}"
                data-refresh-route="/news/${newsItem.id}" 
            >
                ${isSaved ? '🗑️ Von Gemerkten News entfernen' : '💾 Für Offline speichern (Merken)'}
            </button>
            
            <hr>

            <p><strong>${newsItem.teaser}</strong></p>
            <div class="content">
                ${newsItem.content}
            </div>
            
        </article>
    `;
}
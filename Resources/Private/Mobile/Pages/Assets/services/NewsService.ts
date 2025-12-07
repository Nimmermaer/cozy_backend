// src/services/NewsService.ts

import {NewsItem} from '../models/types';

const LOCAL_STORAGE_KEY = 'savedNews';

const API_URL = window.location.origin + "/news.mobile";

// Beispiel-Datenstruktur für den Fall, dass die API nicht erreichbar ist
const FALLBACK_NEWS: NewsItem[] = [
    {
        id: 101,
        title: 'News:',
        teaser: 'Gerade offline',
        content: '',
        date: '2025-12-01'
    },
];

export class NewsService {

    /**
     * Ruft die News von der JSON-Schnittstelle ab.
     */
    public async fetchAllNews(): Promise<NewsItem[]> {
        // Wir prüfen zuerst den Online-Status für eine bessere User Experience
        if (!navigator.onLine) {
            console.warn('Offline: Newsliste kann nicht von der API geladen werden.');
            // Im Offline-Fall zeigen wir nur die lokal gespeicherten News (wenn vorhanden)
            // oder die Fallback-Liste, falls keine gespeichert sind.
            return this.getSavedNews();
        }

        try {
            const response = await fetch(API_URL);

            if (!response.ok) {
                // Bei HTTP-Fehlern (4xx, 5xx) nutzen wir einen Fallback
                console.error(`API-Fehler: ${response.status}`);
                return FALLBACK_NEWS;
            }

            const data: NewsItem[] = await response.json();

            // WICHTIG: Map ID zu Number, falls JSON sie als String liefert
            return data.map(item => ({
                ...item,
                id: Number(item.id)
            }));

        } catch (error) {
            console.error('Netzwerk- oder Parsing-Fehler beim Abrufen der News:', error);
            // Bei Netzwerkproblemen geben wir die Fallback-Liste zurück
            return FALLBACK_NEWS;
        }
    }

    /**
     * Ruft eine einzelne News-Meldung ab (entweder API oder Fallback).
     */
    public async fetchNewsById(id: number): Promise<NewsItem | undefined> {
        // Im echten TYPO3-System würden Sie hier vielleicht einen Detail-Endpunkt aufrufen.
        // Für dieses Beispiel laden wir die gesamte Liste und filtern.
        const allNews = await this.fetchAllNews();
        const newsItem = allNews.find(n => n.id === id);

        // Wenn die API offline ist, prüfen wir auch den LocalStorage,
        // falls die News dort gespeichert wurde.
        if (!newsItem && !navigator.onLine) {
            return this.getSavedNews().find(n => n.id === id);
        }

        return newsItem;
    }

    // --- LocalStorage Logik ---

    /**
     * Lädt alle gespeicherten News-Artikel aus dem LocalStorage.
     */
    public getSavedNews(): NewsItem[] {
        const json = localStorage.getItem(LOCAL_STORAGE_KEY);
        if (!json) return [];

        try {
            // Sicherstellen, dass die geparsten Objekte den NewsItem Typ erfüllen
            return JSON.parse(json) as NewsItem[];
        } catch (e) {
            console.error('Fehler beim Parsen der gespeicherten News:', e);
            return [];
        }
    }

    /**
     * Fügt einen Artikel zur LocalStorage-Liste hinzu.
     */
    public saveNewsItem(newsItem: NewsItem): void {
        const saved = this.getSavedNews();
        // Nur hinzufügen, wenn es noch nicht gespeichert ist
        if (!saved.some(n => n.id === newsItem.id)) {
            saved.push(newsItem);
            this.saveNews(saved);
        }
    }

    /**
     * Entfernt einen Artikel aus der LocalStorage-Liste.
     */
    public removeNewsItem(id: number): void {
        let saved = this.getSavedNews();
        saved = saved.filter(n => n.id !== id);
        this.saveNews(saved);
    }

    /**
     * Prüft, ob ein Artikel bereits gespeichert ist.
     */
    public isNewsSaved(id: number): boolean {
        return this.getSavedNews().some(n => n.id === id);
    }

    /**
     * Speichert die aktuelle Liste der gemerkten News im LocalStorage.
     */
    private saveNews(news: NewsItem[]): void {
        localStorage.setItem(LOCAL_STORAGE_KEY, JSON.stringify(news));
    }
}
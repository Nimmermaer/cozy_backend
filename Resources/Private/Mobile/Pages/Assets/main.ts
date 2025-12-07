import './style.scss';
import {AppRouter} from './services/AppRouter';
import {NewsItem, Route} from './models/types';
import {renderImprintView, renderNewsDetailView, renderNewsListView, renderSavedNewsView} from './views/ViewRenderers';
import { NewsService } from './services/NewsService';
import { PushService } from './services/PushService'; // NEU

const newsService = new NewsService();
const pushService = new PushService(); // NEU
let router: AppRouter;


const routes: Route[] = [
    {
        path: '/',
        title: 'Aktuelle News',
        render: renderNewsListView
    },
    {
        path: '/saved',
        title: 'Gemerkte News',
        render: renderSavedNewsView
    },
    {
        path: '/imprint',
        title: 'Impressum',
        render: renderImprintView
    },
    {
        path: '/news/:id',
        title: 'News-Detail',
        // WICHTIG: Die Render-Funktion bekommt die URL-Parameter
        render: (params) => renderNewsDetailView(params?.id || 'ID nicht gefunden')
    }
];

document.addEventListener('DOMContentLoaded', () => {
    router = new AppRouter(routes);

    router.updateSavedNewsCount(newsService.getSavedNews().length);

    document.getElementById('app-content')?.addEventListener('click', handleSaveToggle);
    document.getElementById('subscribe-push')?.addEventListener('click', () => {
        pushService.subscribeToPush();
    });

   // console.log("Anwendung ist bereit.");
});


async function handleSaveToggle(event: Event): Promise<void> {
    const target = event.target as HTMLElement;

    if (target.matches('[data-action="toggle-save"]')) {
        event.preventDefault(); // Verhindere Standardaktion, falls es ein Link wäre

        const id = Number(target.getAttribute('data-id'));
        if (isNaN(id)) return;

        let newsItemToSave: NewsItem | undefined;

        if (newsService.isNewsSaved(id)) {
            // 1. Entfernen
            newsService.removeNewsItem(id);
        } else {
            // 2. Merken
            // Wenn wir speichern, MÜSSEN wir die News-Details laden (damit Content
            // im LocalStorage für Offline-Verfügbarkeit ist)
            newsItemToSave = await newsService.fetchNewsById(id);
            if (newsItemToSave) {
                newsService.saveNewsItem(newsItemToSave);
            }
        }

        // 3. UI aktualisieren (Zähler in der Navigation)
        router.updateSavedNewsCount(newsService.getSavedNews().length);

        // 4. UI aktualisieren (Ansicht)
        // Durch das Neuladen der Route stellen wir sicher, dass der Button-Status
        // auf der Detailseite oder die gesamte Liste der gemerkten News
        // sofort aktualisiert wird.
        await router.handleRouting();
    }
}
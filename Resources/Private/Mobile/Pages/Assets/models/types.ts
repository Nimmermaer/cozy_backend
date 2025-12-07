export interface NewsItem {
    id: number;
    title: string;
    teaser: string;
    content: string;
    date: string;
}

export type RoutePath = '/' | '/saved' | '/imprint' | '/news/:id';

export interface Route {
    path: RoutePath;
    title: string;
    render: (params?: { id?: string } | undefined) => Promise<string>;
}

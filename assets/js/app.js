import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter } from 'react-router-dom';

import AppreciationEvent from './components/event/AppreciationEvent';
import CommentEvent from './components/event/CommentEvent';
import Events from './components/event/Events';
import VoteAward from './components/event/VoteAward';
import AchatTicket from './components/event/AchatTicket';
import PlayRoutes from './components/play/PlayRoutes';

import { I18nextProvider } from 'react-i18next';
import i18n from './components/i18n'
import NewsRoutes from './components/news/NewsRoutes';



;

if (document.getElementById('kossa_event_events'))
    ReactDOM.render(<Events />, document.getElementById('kossa_event_events'));
if (document.getElementById('kossa_event_event_appreciation'))
    ReactDOM.render(<AppreciationEvent />, document.getElementById('kossa_event_event_appreciation'));
if (document.getElementById('kossa_event_event_comment'))
    ReactDOM.render(<CommentEvent />, document.getElementById('kossa_event_event_comment'));
if (document.getElementById('kossa_event_award_categorie'))
    ReactDOM.render(<VoteAward />, document.getElementById('kossa_event_award_categorie'));
if (document.getElementById('kossa_event_ticket_achat'))
    ReactDOM.render(<AchatTicket />, document.getElementById('kossa_event_ticket_achat'));

if (document.getElementById('kossa_play_index')) {
    i18n.changeLanguage(window.locale);
    ReactDOM.render(
        <I18nextProvider i18n={i18n}>
            <BrowserRouter>
                <PlayRoutes />
            </BrowserRouter>
        </I18nextProvider>,
        document.getElementById("kossa_play_index")
    )
}

if (document.getElementById('kossa_news_index')) {
    i18n.changeLanguage(window.locale);
    ReactDOM.render(
        <I18nextProvider i18n={i18n}>
            <BrowserRouter>
                <NewsRoutes />
            </BrowserRouter>
        </I18nextProvider>,
        document.getElementById("kossa_news_index")
    )
}





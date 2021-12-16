import React from 'react';
import axios from 'axios';
import TimeAgo from 'react-timeago'
import frenchStrings from 'react-timeago/lib/language-strings/fr'
import englishStrings from 'react-timeago/lib/language-strings/en'
import buildFormatter from 'react-timeago/lib/formatters/buildFormatter'
import InfiniteScroll from 'react-infinite-scroller';

class Events extends React.Component {

    constructor(props) {
        super(props);
        this.number = 10; //d'evenements a charger
        this.state = {
            evenements: [],
            hasMoreItems: true,
        };
    }

    handleLoadMore(page) {
        let limit = this.number;
        let offset = (page - 1) * this.number;
        axios.get('/' + window.locale + '/event/json_evenements/' + offset + '/' + limit + '/' + window.kossa_event_type + '/' + window.kossa_event_query)
            .then(response => {
                //console.log(response.data)
                if (response.data.length < this.number) {
                    this.setState({
                        hasMoreItems: false
                    });
                }
                this.setState({
                    evenements: [...new Set(this.state.evenements.concat(response.data))]
                });
            })
            .catch(error => console.log(error));
    }

    render() {
        //console.log('accueil',window.accueil)
        return (
            <div >
                <InfiniteScroll
                    pageStart={0}
                    loadMore={this.handleLoadMore.bind(this)}
                    hasMore={this.state.hasMoreItems}
                    loader={< div className="loader" key={0}>Chargement des evenements...</div>}
                >
                    {this.state.evenements.map(
                        ({ id, link, titre, date_debut, date_fin, photo_url, categorie_event, tickets }) => (
                            <a key={id} href={link} className="une-event" >
                                <div className="mb-4">
                                    {window.accueil == 1 &&
                                        <div className="p-3 white brd-bt-event toile-content">
                                            <span className={'text-md-left d-inline-block ls3 pl-3 pr-3 badge ' + categorie_event.color}>{categorie_event.nom}</span>
                                        </div>
                                    }
                                    <div className="actu-news-img position-relative brd-bt-event-2" style={{ backgroundImage: `url(${photo_url})` }}>
                                        <div className=" lct-event trs-03 position-absolute h-100 w-100"></div>
                                        <div className="h5-responsive pl-3 ls3 position-absolute text-white" style={{ top: '15px', left: '10px' }}>
                                            {titre}
                                        </div>

                                        <div className="h6-responsive ls3 position-absolute font-weight-bold white z-depth-2 pt-2 cl-n1 pb-2 pl-4 pr-4" style={{ bottom: '20px', right: '30px' }}>
                                            {date_fin == null &&
                                                <span>Le <span></span>
                                                    {new Intl.DateTimeFormat(window.locale, {
                                                        year: 'numeric',
                                                        month: 'long',
                                                        day: '2-digit'
                                                    }).format(new Date(date_debut))}
                                                </span>
                                            }

                                            {date_fin != null &&
                                                <span>Du <span></span>
                                                    {new Intl.DateTimeFormat(window.locale, {
                                                        year: 'numeric',
                                                        month: 'long',
                                                        day: '2-digit'
                                                    }).format(new Date(date_debut))}

                                                    <span></span> au <span></span>

                                                    {new Intl.DateTimeFormat(window.locale, {
                                                        year: 'numeric',
                                                        month: 'long',
                                                        day: '2-digit'
                                                    }).format(new Date(date_fin))}
                                                </span>

                                            }
                                        </div>
                                        {tickets.length > 0 &&
                                            <div className="btn position-relative btn-danger h6-responsive ls3 position-absolute font-weight-bold white z-depth-2 pt-2 cl-n1 pb-2 pl-4 pr-4 font-weight-bold" style={{ bottom: '60px', right: '30px' }}>
                                                Ticket
                </div>
                                        }
                                    </div>
                                </div>

                            </a>
                        )
                    )}
                </InfiniteScroll>

            </div>
        );
    }
}

export default Events;



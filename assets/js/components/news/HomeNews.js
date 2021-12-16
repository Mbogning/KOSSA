import React from 'react';
import Banner from '../../components/news/Banner';
import Base from '../../components/news/Base';
import ContentRightMidle from '../../components/news/ContentRightMidle';
import axios from 'axios';
import TimeAgo from 'react-timeago'
import frenchStrings from 'react-timeago/lib/language-strings/fr'
import englishStrings from 'react-timeago/lib/language-strings/en'
import buildFormatter from 'react-timeago/lib/formatters/buildFormatter'
import InfiniteScroll from 'react-infinite-scroller';
import { NavLink } from 'react-router-dom';
import * as constant from '../Constant';


class HomeNews extends React.Component {
    constructor(props) {
        super(props);
        this.type = "empty";
        this.query = "empty";

        const { location: { search } } = this.props;

        //console.log('search', search)

        if (search.startsWith("?query")) {
            this.type = "query"
            this.query = search.replace("?query=", "");
        } else if (search.startsWith("?category")) {
            this.type = "category"
            this.query = search.replace("?category=", "");
        } else if (search.startsWith("?tag")) {
            this.type = "tag"
            this.query = search.replace("?tag=", "");
        }

        this.number = 10; //d'articles a charger
        this.state = {
            articles: [],
            categories: [],
            hasMoreItems: true,
        };
    }

    componentDidMount() {
        

        const script = document.createElement("script");
        script.src = "/kossa/js/custom.js";
        script.async = true;
        document.body.appendChild(script);

        axios.get('/' + window.locale + '/news/json_categories')
            .then(response => {

                this.setState({
                    categories: response.data
                });
            })
            .catch(error => console.log(error));
    }


    handleLoadMore(page) {
        let limit = this.number;
        let offset = (page - 1) * this.number;

        console.log('type', this.type)
        console.log('query', this.query)
        axios.get('/' + window.locale + '/news/json_articles/' + offset + '/' + limit + '/' + this.type + '/' + this.query)
            .then(response => {
                console.log(response.data)
                if (response.data.length < this.number) {
                    this.setState({
                        hasMoreItems: false
                    });
                }
                this.setState({
                    articles: [...new Set(this.state.articles.concat(response.data))]
                });
            })
            .catch(error => console.log(error));
    }


    render() {

        let formatter
        if (window.locale === 'fr') {
            formatter = buildFormatter(frenchStrings)
        } else {
            formatter = buildFormatter(englishStrings)
        }

        return (
            <Base
                banner={<Banner />}
                contentRight={<ContentRightMidle />}
                content={
                    <div >
                        <div className="h3-responsive float-left font-weight-bold mt-1 mb-2 mb-md-0">Kossa news: à la une</div>
                        <div className="text-right  mb-4">
                            <div className="">
                                <span className="btn position-relative btn-danger btn-sm p-1 pl-3 pr-3 font-weight-bold btn-cate">Catégorie: <span className="chx-cate">#Toute</span>
                                    <div className="position-absolute d-none btn-danger p-0 w-100 text-left lst-elm-cate z-depth-2 animated " style={{ top: '99%', left: '0px' }}>
                                        {this.state.categories.map(
                                            ({ id, nom }) => (
                                                <div key={id} className="p-1 pl-3 pr-3 font-weight-bold fs-12 text-uppercase ls3 elm-cate">
                                                <a href={`${constant.NEWS_URL}?category=${nom}`} >
                                                    #{nom}
                                                </a>
                                                </div>
                                            )
                                        )}
                                    </div>
                                </span>

                            </div>
                        </div>
                        <InfiniteScroll
                            pageStart={0}
                            loadMore={this.handleLoadMore.bind(this)}
                            hasMore={this.state.hasMoreItems}
                            loader={<div className="loader" key={0}>Chargement des articles...</div>}
                        >
                            {this.state.articles.map(
                                ({ id, slug, title, published_at, photo_url, categorie }) => (

                                    <NavLink key={id} to={`${constant.NEWS_URL}/article/${slug}`} className="une-news">
                                        <div className="mb-4">
                                            <div className="p-3 white brd-bt-news toile-content">
                                                <span className={'text-md-left d-inline-block badge ls3 pl-3 pr-3 ' + categorie.color} >{categorie.nom}</span>
                                                <span className="float-right fs-13 ls3"> <TimeAgo date={published_at} formatter={formatter} /></span>
                                            </div>
                                            <div className="actu-news-img brd-bt-news position-relative" style={{ backgroundImage: `url(${photo_url})` }}>
                                                <div className="couv position-absolute h-100 w-100"></div>
                                            </div>
                                            <div className="p-4 white toile-content">
                                                <div className="h5-responsive font-weight-bold text-uppercase ls3">
                                                    {title}
                                                </div>
                                            </div>
                                        </div>
                                    </NavLink>
                                )
                            )}
                        </InfiniteScroll>

                    </div>
                }
            >
            </Base>
        );
    }
}

export default HomeNews;





















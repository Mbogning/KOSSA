import React from 'react';
import { Route, Switch } from 'react-router-dom';
import * as constant from '../Constant';
import HomeNews from './HomeNews';
import Genre from './Genre';
import DetailArticle from './DetailArticle';
import DetailGenre from './DetailGenre';
import NotFound from './NotFound';

export default class NewsRoutes extends React.Component {
  render() {
    return (
      <div>
        <Switch>
          <Route exact path={constant.NEWS_URL + '/'} component={HomeNews} />
          <Route exact path={constant.NEWS_URL + '/article/:articleSlug'} component={DetailArticle} />
          <Route exact path={constant.NEWS_URL + '/genre/:genreSlug'} component={DetailGenre} />
          <Route exact path={constant.NEWS_URL + '/genre'} component={Genre} />
          <Route component={NotFound} />
        </Switch>
      </div>
    );
  }
}




















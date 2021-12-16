import React from 'react';
import Comments from '../../components/Comments';
import axios from 'axios';

class CommentEvent extends React.Component {


    constructor(props) {
        super(props);
        this.number=10; //le nombre de commentaires a charger
        this.state = {
            content: '',
            comments: [],
            hasMoreItems: true,
        };

        this.handleContentChange = this.handleContentChange.bind(this); 
    }

    
    handleLoadMore(page) {
        let offset=(page-1)*this.number;
        axios.get('/' + window.locale + '/event/json_eventcomment/' + window.eventEventId+'/'+offset+'/'+this.number)
            .then(response => {
                console.log(response.data)
                if(response.data.length<this.number){
                    this.setState({
                        hasMoreItems: false
                    }); 
                }
                this.setState({
                    comments:  [...new Set(this.state.comments.concat(response.data))]  
                });
            })
            .catch(error => console.log(error));
    }


    handlePublierClick() {
        let content = this.state.content;
        if(!content){
            return;
        }

        axios.post('/' + window.locale + '/event/json_eventcommentpublier/' + window.eventEventId, {
            comment: content
        }).then(response => {
            let comments = this.state.comments;
            comments.unshift(response.data)
            console.log(response.data)
            this.setState({
                comments: comments,
            });
            this.setState({content: ''});
        }).catch(error => console.log(error));
    }

    handleContentChange(event) {
        this.setState({content: event.target.value});
      }

    render() {
       
        return (
            <div >

                <Comments
                    login={window.login}
                    content={this.state.content}
                    comments={this.state.comments}
                    loadFunc={this.handleLoadMore.bind(this)}
                    hasMoreItems={this.state.hasMoreItems}
                    locale={window.locale}
                    color="event"
                    onContentChange={this.handleContentChange}
                    onPublierClick={() => this.handlePublierClick()}
                />
            </div>
        );
    }
}

export default CommentEvent;



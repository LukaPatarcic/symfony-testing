import React,{Component} from 'react';
import ReactDOM from 'react-dom';
import ReactHtmlParser, { processNodes, convertNodeToElement, htmlparser2 } from 'react-html-parser';
import 'bootstrap'
import '../css/app.css'
// import JSON from '../libs/db';

//Componets
import Header from './components/header';
import NewsList from './components/news_list';

export default class App extends Component{

    state = {
        news: JSON.parse(window.DATA),
        filtered: []
    }

    getKeywords = (e) => {
        let keyword = e.target.value;
        let filtered = this.state.news.filter((item) => {
            return item.title.indexOf(keyword) > -1
        });

        this.setState({
            filtered
        });

        console.log(filtered);
    }

    render() {

        let allNews = this.state.news;
        let filteredNews = this.state.filtered;
        return (
        <div>
            <Header keywords={this.getKeywords}/>
            <div className={"container"}>
                <div className={"row"}>
                    <div className={"col-sm-12"}>
                        <NewsList
                            news={filteredNews.length === 0 ? allNews : filteredNews}>
                        Hello World!
                        </NewsList>
                    </div>
                </div>
            </div>
        </div>
        );
    }
}
ReactDOM.render(<App/>,document.getElementById('root'));